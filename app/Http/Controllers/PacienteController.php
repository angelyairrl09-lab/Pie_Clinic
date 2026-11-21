<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Http\Requests\PacienteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => 'nullable|string|max:150',
            'estado' => 'nullable|in:1,0'
        ]);

        $buscar = trim($filtros['buscar'] ?? '');
        $estado = $filtros['estado'] ?? null;

        $pacientes = Paciente::query()

            ->when($buscar !== '', function ($query) use ($buscar) {

                $query->where(function ($q) use ($buscar) {

                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                      ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                      ->orWhere('numero_paciente', 'like', "%{$buscar}%")
                      ->orWhere('telefono', 'like', "%{$buscar}%")
                      ->orWhere('correo', 'like', "%{$buscar}%")
                      ->orWhereRaw(
                          "CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?",
                          ["%{$buscar}%"]
                      );
                });

            })

            ->when($estado !== null, function ($query) use ($estado) {

                $query->where('activo', $estado);

            })

            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        $totalPacientes = Paciente::count();

        $totalActivos = Paciente::where('activo', true)
                               ->count();

        $totalInactivos = Paciente::where('activo', false)
                                 ->count();

        $totalNuevos = Paciente::where(
            'created_at',
            '>=',
            now()->startOfMonth()
        )->count();

        return view('pacientes.index', compact(
            'pacientes',
            'totalPacientes',
            'totalActivos',
            'totalInactivos',
            'totalNuevos'
        ));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(PacienteRequest $request)
    {
        $paciente = DB::transaction(function () use ($request) {

            $paciente = new Paciente($request->validated());

            $paciente->registrado_por = $request->user()->id;
            $paciente->activo = true;

            $paciente->save();

            $paciente->numero_paciente = 'PC-' . str_pad(
                $paciente->id_paciente,
                6,
                '0',
                STR_PAD_LEFT
            );

            $paciente->save();

            return $paciente;
        });

        return redirect()
            ->route('pacientes.show', $paciente)
            ->with('success', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load('usuarioRegistro');

        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(
        PacienteRequest $request,
        Paciente $paciente
    ) {
        $paciente->update($request->validated());

        return redirect()
            ->route('pacientes.show', $paciente)
            ->with('success', 'Información actualizada correctamente.');
    }

    public function cambiarEstado(
        Request $request,
        Paciente $paciente
    ) {
        $datos = $request->validate([
            'activo' => 'required|boolean'
        ]);

        $paciente->update([
            'activo' => $datos['activo']
        ]);

        $mensaje = $paciente->activo
            ? 'Paciente activado correctamente.'
            : 'Paciente desactivado correctamente.';

        return redirect()
            ->route('pacientes.index')
            ->with('success', $mensaje);
    }
}
