<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CitaController extends Controller
{
    private const ESTADOS = [
        'PENDIENTE',
        'CONFIRMADA',
        'ATENDIDA',
        'CANCELADA',
        'NO_ASISTIO'
    ];

    private const ESTADOS_ACTIVOS = [
        'PENDIENTE',
        'CONFIRMADA'
    ];

    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => 'nullable|string|max:150',
            'fecha' => 'nullable|date',
            'estado' => ['nullable', Rule::in(self::ESTADOS)]
        ]);

        $citas = Cita::with('paciente')
            ->when($filtros['buscar'] ?? null, function ($q, $buscar) {
                $q->whereHas('paciente', function ($p) use ($buscar) {
                    $p->where('numero_paciente', 'like', "%{$buscar}%")
                      ->orWhereRaw(
                          "CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?",
                          ["%{$buscar}%"]
                      );
                });
            })
            ->when($filtros['fecha'] ?? null, function ($q, $fecha) {
                $q->whereDate('fecha_cita', $fecha);
            })
            ->when($filtros['estado'] ?? null, function ($q, $estado) {
                $q->where('estado', $estado);
            })
            ->orderByDesc('fecha_cita')
            ->orderBy('hora_inicio')
            ->paginate(15)
            ->withQueryString();

        $citasHoy = Cita::whereDate('fecha_cita', today())
            ->whereIn('estado', self::ESTADOS_ACTIVOS)
            ->count();

        $pendientes = Cita::where('estado', 'PENDIENTE')->count();

        $confirmadas = Cita::where('estado', 'CONFIRMADA')->count();

        $atendidas = Cita::where('estado', 'ATENDIDA')->count();

        return view('citas.index', compact(
            'citas',
            'citasHoy',
            'pendientes',
            'confirmadas',
            'atendidas'
        ));
    }

    public function create()
    {
        $pacientes = Paciente::where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->get();

        return view('citas.create', compact('pacientes'));
    }

    private function validar(
        Request $request,
        bool $esEdicion = false
    ): array {
        $reglas = [
            'fecha_cita' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'duracion_minutos' => 'required|integer|min:10|max:240',
            'motivo' => 'required|string|max:200',
            'observaciones' => 'nullable|string|max:5000'
        ];

        if (!$esEdicion) {
            $reglas['id_paciente'] = [
                'required',
                Rule::exists('pc_pacientes', 'id_paciente')
                    ->where('activo', true)
            ];
        }

        return $request->validate($reglas);
    }

    private function bloquearAgenda(): void
    {
        $bloqueo = DB::table('pc_agenda_bloqueo')
            ->where('id', 1)
            ->lockForUpdate()
            ->first();

        if (!$bloqueo) {
            throw new \RuntimeException(
                'No existe el registro de bloqueo de agenda.'
            );
        }
    }

    private function verificarHorario(
        array $datos,
        ?int $excluirId = null,
        bool $validarPasado = true
    ): void {
        $inicio = Carbon::parse(
            $datos['fecha_cita'] . ' ' . $datos['hora_inicio']
        );

        $fin = $inicio->copy()->addMinutes(
            (int) $datos['duracion_minutos']
        );

        if ($fin->toDateString() !== $inicio->toDateString()) {
            throw ValidationException::withMessages([
                'hora_inicio' =>
                    'La cita debe terminar el mismo día.'
            ]);
        }

        if ($validarPasado && $inicio->lt(now())) {
            throw ValidationException::withMessages([
                'fecha_cita' =>
                    'No puedes programar una cita en el pasado.'
            ]);
        }

        $ocupadas = Cita::whereDate(
                'fecha_cita',
                $datos['fecha_cita']
            )
            ->whereIn('estado', self::ESTADOS_ACTIVOS)
            ->when($excluirId !== null, function ($q) use ($excluirId) {
                $q->where('id_cita', '!=', $excluirId);
            })
            ->get();

        foreach ($ocupadas as $cita) {
            $inicioOcupado = Carbon::parse(
                $cita->fecha_cita->format('Y-m-d') .
                ' ' . $cita->hora_inicio
            );

            $finOcupado = $inicioOcupado->copy()
                ->addMinutes($cita->duracion_minutos);

            if (
                $inicio->lt($finOcupado) &&
                $fin->gt($inicioOcupado)
            ) {
                throw ValidationException::withMessages([
                    'hora_inicio' =>
                        'El horario se traslapa con otra cita.'
                ]);
            }
        }
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        DB::transaction(function () use ($datos, $request) {
            $this->bloquearAgenda();

            $this->verificarHorario($datos);

            Cita::create([
                ...$datos,
                'estado' => 'PENDIENTE',
                'registrado_por' => $request->user()->id
            ]);
        });

        return redirect()
            ->route('citas.index')
            ->with('success', 'Cita registrada correctamente.');
    }

    public function show(Cita $cita)
    {
        $cita->load(['paciente', 'usuarioRegistro']);

        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        if (!in_array($cita->estado, self::ESTADOS_ACTIVOS)) {
            return redirect()
                ->route('citas.show', $cita)
                ->with('error', 'Esta cita ya no puede reprogramarse.');
        }

        $cita->load('paciente');

        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, Cita $cita)
    {
        $datos = $this->validar($request, true);

        DB::transaction(function () use ($cita, $datos) {
            $this->bloquearAgenda();

            $cita = Cita::whereKey($cita->id_cita)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($cita->estado, self::ESTADOS_ACTIVOS)) {
                throw ValidationException::withMessages([
                    'estado' => 'Esta cita ya no puede reprogramarse.'
                ]);
            }

            $this->verificarHorario($datos, $cita->id_cita);

            $cita->update($datos);
        });

        return redirect()
            ->route('citas.show', $cita)
            ->with('success', 'Cita actualizada correctamente.');
    }

    public function cambiarEstado(Request $request, Cita $cita)
    {
        $datos = $request->validate([
            'estado' => [
                'required',
                Rule::in(self::ESTADOS)
            ]
        ]);

        DB::transaction(function () use ($cita, $datos) {
            $this->bloquearAgenda();

            $cita = Cita::whereKey($cita->id_cita)
                ->lockForUpdate()
                ->firstOrFail();

            $nuevoEstado = $datos['estado'];

            if ($nuevoEstado === $cita->estado) {
                return;
            }

            // No reabrir citas que ya terminaron.
            if (!in_array($cita->estado, self::ESTADOS_ACTIVOS)) {
                throw ValidationException::withMessages([
                    'estado' =>
                        'No puedes modificar una cita finalizada.'
                ]);
            }

            // Comprobar horarios antes de confirmar.
            if (in_array($nuevoEstado, self::ESTADOS_ACTIVOS)) {
                $this->verificarHorario([
                    'fecha_cita' => $cita->fecha_cita->format('Y-m-d'),
                    'hora_inicio' => substr($cita->hora_inicio, 0, 5),
                    'duracion_minutos' => $cita->duracion_minutos
                ], $cita->id_cita, false);
            }

            $cita->update([
                'estado' => $nuevoEstado
            ]);
        });

        return redirect()
            ->route('citas.show', $cita)
            ->with('success', 'Estado actualizado correctamente.');
    }
}
