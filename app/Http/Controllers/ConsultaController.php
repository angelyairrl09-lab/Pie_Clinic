<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Expediente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConsultaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $consultas = Consulta::with('paciente')
            ->when($buscar !== '', fn ($q) => $q->whereHas('paciente', function ($p) use ($buscar) {
                $p->where(function ($names) use ($buscar) {
                    $names->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                        ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                        ->orWhere('numero_paciente', 'like', "%{$buscar}%");
                });
            }))
            ->orderByDesc('fecha_consulta')->orderByDesc('id_consulta')
            ->paginate(10)->withQueryString();
        $totalConsultas = Consulta::count();
        return view('consultas.index', compact('consultas', 'totalConsultas'));
    }

    public function create()
    {
        $expedientes = Expediente::with('paciente')
            ->whereHas('paciente', fn ($q) => $q->where('activo', 1))
            ->get()->sortBy(fn ($e) => $e->paciente?->nombre_completo)->values();
        $tratamientos = Tratamiento::orderBy('nombre')->get();
        return view('consultas.create', compact('expedientes', 'tratamientos'));
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $expediente = Expediente::findOrFail($datos['id_expediente']);
        $datos['id_paciente'] = $expediente->id_paciente;
        $datos['registrado_por'] = auth()->id();
        $consulta = Consulta::create($datos);
        return redirect()->route('consultas.show', $consulta)
            ->with('success', 'Consulta registrada correctamente.');
    }

    public function show(Consulta $consulta)
    {
        $consulta->load(['paciente', 'expediente', 'tratamiento', 'usuarioRegistro']);
        return view('consultas.show', compact('consulta'));
    }

    public function edit(Consulta $consulta)
    {
        $consulta->load('paciente');
        $tratamientos = Tratamiento::where('id_paciente', $consulta->id_paciente)
            ->orderBy('nombre')->get();
        return view('consultas.edit', compact('consulta', 'tratamientos'));
    }

    public function update(Request $request, Consulta $consulta)
    {
        $datos = $this->validar($request, $consulta);
        $consulta->update($datos);
        return redirect()->route('consultas.show', $consulta)
            ->with('success', 'Consulta actualizada correctamente.');
    }

    private function validar(Request $request, ?Consulta $consulta = null): array
    {
        $rules = [
            'fecha_consulta' => ['required', 'date'],
            'hora_consulta' => ['nullable', 'date_format:H:i'],
            'motivo_consulta' => ['required', 'string'],
            'exploracion' => ['nullable', 'string'],
            'diagnostico' => ['nullable', 'string'],
            'procedimiento' => ['nullable', 'string'],
            'indicaciones' => ['nullable', 'string'],
            'observaciones' => ['nullable', 'string'],
            'proxima_revision' => ['nullable', 'date', 'after_or_equal:fecha_consulta'],
        ];
        if ($consulta === null) {
            $rules['id_expediente'] = ['required', 'integer', Rule::exists('pc_expedientes', 'id_expediente')];
        }
        $idPaciente = $consulta?->id_paciente;
        if ($consulta === null && $request->filled('id_expediente')) {
            $idPaciente = Expediente::whereKey($request->input('id_expediente'))->value('id_paciente');
        }
        $rules['id_tratamiento'] = [
            'nullable', 'integer',
            Rule::exists('pc_tratamientos', 'id_tratamiento')->where('id_paciente', $idPaciente ?? 0),
        ];
        return $request->validate($rules);
    }
}
