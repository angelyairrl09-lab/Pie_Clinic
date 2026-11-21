<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TratamientoController extends Controller
{
    private const ESTADOS = [
        'PENDIENTE',
        'ACTIVO',
        'FINALIZADO',
        'CANCELADO',
    ];

    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => 'nullable|string|max:150',
            'estado' => ['nullable', Rule::in(self::ESTADOS)],
        ]);

        $tratamientos = Tratamiento::with('paciente')
            ->when($filtros['buscar'] ?? null, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhereHas('paciente', function ($p) use ($buscar) {
                          $p->where('numero_paciente', 'like', "%{$buscar}%")
                            ->orWhereRaw(
                                "CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?",
                                ["%{$buscar}%"]
                            );
                      });
                });
            })
            ->when($filtros['estado'] ?? null, function ($query, $estado) {
                $query->where('estado', $estado);
            })
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id_tratamiento')
            ->paginate(15)
            ->withQueryString();

        $totalTratamientos = Tratamiento::count();

        $tratamientosActivos = Tratamiento::where('estado', 'ACTIVO')
            ->count();

        $tratamientosPendientes = Tratamiento::where('estado', 'PENDIENTE')
            ->count();

        $tratamientosFinalizados = Tratamiento::where('estado', 'FINALIZADO')
            ->count();

        return view('tratamientos.index', compact(
            'tratamientos',
            'totalTratamientos',
            'tratamientosActivos',
            'tratamientosPendientes',
            'tratamientosFinalizados'
        ));
    }

    public function create()
    {
        $pacientes = Paciente::where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get();

        return view('tratamientos.create', compact('pacientes'));
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_paciente' => [
                'required',
                Rule::exists('pc_pacientes', 'id_paciente')
                    ->where('activo', true),
            ],
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:10000',
            'indicaciones' => 'nullable|string|max:10000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'costo' => 'nullable|numeric|min:0|max:99999999.99',
            'estado' => ['required', Rule::in(self::ESTADOS)],
        ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        $datos['registrado_por'] = $request->user()->id;

        $tratamiento = Tratamiento::create($datos);

        return redirect()
            ->route('tratamientos.show', $tratamiento)
            ->with('success', 'Tratamiento registrado correctamente.');
    }

    public function show(Tratamiento $tratamiento)
    {
        $tratamiento->load(['paciente', 'usuarioRegistro']);

        return view('tratamientos.show', compact('tratamiento'));
    }

    public function edit(Tratamiento $tratamiento)
    {
        $pacientes = Paciente::where('activo', true)
            ->orWhere('id_paciente', $tratamiento->id_paciente)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get();

        return view('tratamientos.edit', compact(
            'tratamiento',
            'pacientes'
        ));
    }

    public function update(Request $request, Tratamiento $tratamiento)
    {
        $datos = $this->validar($request);

        $tratamiento->update($datos);

        return redirect()
            ->route('tratamientos.show', $tratamiento)
            ->with('success', 'Tratamiento actualizado correctamente.');
    }

    public function destroy(Tratamiento $tratamiento)
    {
        abort(405, 'Los tratamientos clínicos no se eliminan.');
    }
}
