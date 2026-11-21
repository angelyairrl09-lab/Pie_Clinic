<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\DiagnosticoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class DiagnosticoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => 'nullable|string|max:150'
        ]);

        $buscar = trim($filtros['buscar'] ?? '');

        $diagnosticos = Diagnostico::with('paciente')
            ->withCount('imagenes')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('titulo', 'like', "%{$buscar}%")
                        ->orWhereHas('paciente', function ($p) use ($buscar) {
                            $p->where('numero_paciente', 'like', "%{$buscar}%")
                              ->orWhereRaw(
                                  "CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?",
                                  ["%{$buscar}%"]
                              );
                        });
                });
            })
            ->orderByDesc('fecha_diagnostico')
            ->orderByDesc('id_diagnostico')
            ->paginate(15)
            ->withQueryString();

        return view('diagnosticos.index', compact('diagnosticos'));
    }

    public function create()
    {
        $pacientes = Paciente::where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('nombre')
            ->get();

        return view('diagnosticos.create', compact('pacientes'));
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_paciente' => [
                'required',
                'exists:pc_pacientes,id_paciente'
            ],
            'fecha_diagnostico' => [
                'required', 'date', 'before_or_equal:today'
            ],
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string|max:10000',
            'observaciones' => 'nullable|string|max:10000',
            'recomendaciones' => 'nullable|string|max:10000',
            'imagenes' => 'nullable|array|max:5',
            'imagenes.*' => [
                'required',
                File::image()->max('5mb')
            ]
        ]);
    }

    private function guardarImagenes(
        Request $request,
        Diagnostico $diagnostico
    ): void {
        foreach ($request->file('imagenes', []) as $archivo) {
            $ruta = $archivo->store(
                'diagnosticos/' . $diagnostico->id_diagnostico,
                'local'
            );

            try {
                $diagnostico->imagenes()->create([
                    'ruta_imagen' => $ruta,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'tipo_mime' => $archivo->getMimeType()
                ]);
            } catch (\Throwable $e) {
                Storage::disk('local')->delete($ruta);
                throw $e;
            }
        }
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        unset($datos['imagenes']);

        $diagnostico = Diagnostico::create([
            ...$datos,
            'registrado_por' => $request->user()->id
        ]);

        $this->guardarImagenes($request, $diagnostico);

        return redirect()
            ->route('diagnosticos.show', $diagnostico)
            ->with('success', 'Diagnóstico registrado correctamente.');
    }

    public function show(Diagnostico $diagnostico)
    {
        $diagnostico->load(['paciente', 'imagenes']);

        return view('diagnosticos.show', compact('diagnostico'));
    }

    public function edit(Diagnostico $diagnostico)
    {
        $diagnostico->load(['paciente', 'imagenes']);

        return view('diagnosticos.edit', compact('diagnostico'));
    }

    public function update(
        Request $request,
        Diagnostico $diagnostico
    ) {
        $datos = $this->validar($request);

        unset($datos['imagenes']);

        $diagnostico->update($datos);

        $this->guardarImagenes($request, $diagnostico);

        return redirect()
            ->route('diagnosticos.show', $diagnostico)
            ->with('success', 'Diagnóstico actualizado correctamente.');
    }

    public function imagen(
        Diagnostico $diagnostico,
        DiagnosticoImagen $imagen
    ) {
        abort_unless(
            $imagen->id_diagnostico === $diagnostico->id_diagnostico,
            404
        );

        abort_unless(
            Storage::disk('local')->exists($imagen->ruta_imagen),
            404
        );

        return Storage::disk('local')->response(
            $imagen->ruta_imagen,
            'imagen-clinica-' . $imagen->id_imagen,
            [
                'Content-Type' => $imagen->tipo_mime ?: 'application/octet-stream',
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff'
            ]
        );
    }
}
