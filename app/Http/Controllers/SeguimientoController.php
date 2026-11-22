<?php

namespace App\Http\Controllers;

use App\Models\Seguimiento;
use App\Models\SeguimientoFotografia;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SeguimientoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => 'nullable|string|max:150',
            'id_tratamiento' => 'nullable|integer|exists:pc_tratamientos,id_tratamiento',
        ]);
        $seguimientos = Seguimiento::with(['tratamiento.paciente', 'usuarioRegistro'])
            ->when($filtros['id_tratamiento'] ?? null, fn ($q, $id) => $q->where('id_tratamiento', $id))
            ->when($filtros['buscar'] ?? null, function ($q, $buscar) {
                $q->whereHas('tratamiento', function ($t) use ($buscar) {
                    $t->where('nombre', 'like', "%{$buscar}%")
                      ->orWhereHas('paciente', function ($p) use ($buscar) {
                          $p->where('numero_paciente', 'like', "%{$buscar}%")
                            ->orWhereRaw("CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?", ["%{$buscar}%"]);
                      });
                });
            })
            ->orderByDesc('fecha_consulta')->orderByDesc('id_seguimiento')
            ->paginate(15)->withQueryString();
        return view('seguimientos.index', compact('seguimientos'));
    }

    public function create(Request $request)
    {
        $tratamientos = Tratamiento::with('paciente')->orderByDesc('id_tratamiento')->get();
        $seleccionado = $request->integer('id_tratamiento');
        return view('seguimientos.create', compact('tratamientos', 'seleccionado'));
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_tratamiento' => ['required', Rule::exists('pc_tratamientos', 'id_tratamiento')],
            'fecha_consulta' => 'required|date',
            'observaciones' => 'nullable|string|max:10000',
            'evolucion' => 'nullable|string|max:10000',
            'avances' => 'nullable|string|max:10000',
            'proxima_cita' => 'nullable|date|after:fecha_consulta',
            'fotografias' => 'nullable|array|max:6',
            'fotografias.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        unset($datos['fotografias']);
        $datos['registrado_por'] = $request->user()->id;
        $rutas = [];
        try {
            $seguimiento = DB::transaction(function () use ($request, $datos, &$rutas) {
                $seguimiento = Seguimiento::create($datos);
                foreach ($request->file('fotografias', []) as $archivo) {
                    $ruta = $archivo->store('seguimientos/' . $seguimiento->id_seguimiento, 'public');
                    $rutas[] = $ruta;
                    $seguimiento->fotografias()->create(['ruta' => $ruta, 'nombre_original' => $archivo->getClientOriginalName()]);
                }
                return $seguimiento;
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($rutas);
            throw $e;
        }
        return redirect()->route('seguimientos.show', $seguimiento)->with('success', 'Seguimiento registrado correctamente.');
    }

    public function show(Seguimiento $seguimiento)
    {
        $seguimiento->load(['tratamiento.paciente', 'usuarioRegistro', 'fotografias']);
        $historial = Seguimiento::where('id_tratamiento', $seguimiento->id_tratamiento)
            ->orderByDesc('fecha_consulta')->orderByDesc('id_seguimiento')->get();
        return view('seguimientos.show', compact('seguimiento', 'historial'));
    }

    public function edit(Seguimiento $seguimiento)
    {
        $seguimiento->load('fotografias');
        $tratamientos = Tratamiento::with('paciente')->orderByDesc('id_tratamiento')->get();
        return view('seguimientos.edit', compact('seguimiento', 'tratamientos'));
    }

    public function update(Request $request, Seguimiento $seguimiento)
    {
        $datos = $this->validar($request);
        unset($datos['fotografias']);
        $rutas = [];
        try {
            DB::transaction(function () use ($request, $seguimiento, $datos, &$rutas) {
                $seguimiento->update($datos);
                foreach ($request->file('fotografias', []) as $archivo) {
                    $ruta = $archivo->store('seguimientos/' . $seguimiento->id_seguimiento, 'public');
                    $rutas[] = $ruta;
                    $seguimiento->fotografias()->create(['ruta' => $ruta, 'nombre_original' => $archivo->getClientOriginalName()]);
                }
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($rutas);
            throw $e;
        }
        return redirect()->route('seguimientos.show', $seguimiento)->with('success', 'Seguimiento actualizado correctamente.');
    }

    public function eliminarFotografia(Seguimiento $seguimiento, SeguimientoFotografia $fotografia)
    {
        abort_unless($fotografia->id_seguimiento === $seguimiento->id_seguimiento, 404);
        $ruta = $fotografia->ruta;
        $fotografia->delete();
        Storage::disk('public')->delete($ruta);
        return back()->with('success', 'Fotografía eliminada.');
    }

    public function destroy(Seguimiento $seguimiento)
    {
        abort(405, 'Los seguimientos clínicos no se eliminan.');
    }
}
