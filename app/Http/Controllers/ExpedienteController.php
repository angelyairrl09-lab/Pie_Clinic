<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExpedienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->input('buscar', ''));

        $expedientes = Expediente::with('paciente')
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->whereHas('paciente', function ($q) use ($buscar) {
                    $q->where(function ($pacienteQuery) use ($buscar) {
                        $pacienteQuery
                            ->where('nombre', 'like', "%{$buscar}%")
                            ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                            ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                            ->orWhere('numero_paciente', 'like', "%{$buscar}%");
                    });
                });
            })
            ->latest('id_expediente')
            ->paginate(10)
            ->withQueryString();

        $totalExpedientes = Expediente::count();

        return view('expedientes.index', compact(
            'expedientes',
            'totalExpedientes'
        ));
    }

    public function create()
    {
        $pacientes = Paciente::query()
            ->where('activo', 1)
            ->whereDoesntHave('expediente')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get();

        return view('expedientes.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request, true);

        $datos['registrado_por'] = auth()->id();

        $expediente = DB::transaction(function () use ($datos) {
            return Expediente::create($datos);
        });

        return redirect()
            ->route('expedientes.show', $expediente)
            ->with('success', 'Expediente clínico registrado correctamente.');
    }

    public function show(Expediente $expediente)
    {
        $expediente->load(['paciente', 'usuarioRegistro']);

        return view('expedientes.show', compact('expediente'));
    }

    public function edit(Expediente $expediente)
    {
        $expediente->load('paciente');

        return view('expedientes.edit', compact('expediente'));
    }

    public function update(Request $request, Expediente $expediente)
    {
        $datos = $this->validar($request, false);

        $expediente->update($datos);

        return redirect()
            ->route('expedientes.show', $expediente)
            ->with('success', 'Expediente clínico actualizado correctamente.');
    }

    private function validar(Request $request, bool $creando): array
    {
        $reglas = [
            'fecha_apertura' => ['required', 'date'],
            'tipo_sangre' => ['nullable', 'string', 'max:10'],
            'alergias' => ['nullable', 'string'],
            'enfermedades_cronicas' => ['nullable', 'string'],
            'medicamentos_actuales' => ['nullable', 'string'],
            'antecedentes_personales' => ['nullable', 'string'],
            'antecedentes_familiares' => ['nullable', 'string'],
            'antecedentes_quirurgicos' => ['nullable', 'string'],
            'antecedentes_podologicos' => ['nullable', 'string'],
            'motivo_consulta_inicial' => ['nullable', 'string'],
            'observaciones_generales' => ['nullable', 'string'],
        ];

        if ($creando) {
            $reglas['id_paciente'] = [
                'required',
                'integer',
                Rule::exists('pc_pacientes', 'id_paciente')
                    ->where('activo', 1),
                Rule::unique('pc_expedientes', 'id_paciente'),
            ];
        }

        return $request->validate($reglas);
    }
}
