
@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold">Panel de control</h3>

    <p class="text-secondary">
        Bienvenido, {{ Auth::user()->name }}.
    </p>
</div>

<div class="row g-4">

    @php
        $tarjetas = [
            [
                'titulo' => 'Pacientes registrados',
                'icono' => 'people',
                'total' => $totalPacientes ?? 0
            ],
            [
                'titulo' => 'Citas de hoy',
                'icono' => 'calendar-check',
                'total' => $citasHoy ?? 0
            ],
            [
                'titulo' => 'Tratamientos activos',
                'icono' => 'bandaid',
                'total' => $tratamientosActivos ?? 0
            ],
            [
                'titulo' => 'Seguimientos programados',
                'icono' => 'activity',
                'total' => $seguimientosPendientes ?? 0
            ],
        ];
    @endphp

    @foreach($tarjetas as $tarjeta)

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card pc-card h-100">
                <div class="card-body">

                    <div class="pc-card-icon mb-3">
                        <i class="bi bi-{{ $tarjeta['icono'] }}"></i>
                    </div>

                    <small class="text-secondary">
                        {{ $tarjeta['titulo'] }}
                    </small>

                    <h2 class="fw-bold mt-2 mb-0">
                        {{ $tarjeta['total'] }}
                    </h2>

                </div>
            </div>
        </div>

    @endforeach

    <div class="col-lg-8">
        <div class="card pc-card h-100">
            <div class="card-body">

                <h5 class="fw-semibold mb-4">
                    Próximas citas
                </h5>

                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Fecha y hora</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse(($proximasCitas ?? []) as $cita)

                                <tr>
                                    <td>
                                        {{ $cita->paciente?->nombre_completo ?? 'Sin paciente' }}
                                    </td>

                                    <td>
                                        {{ $cita->fecha_cita
                                            ? \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y')
                                            : 'Sin fecha' }}

                                        {{ $cita->hora_inicio
                                            ? substr((string) $cita->hora_inicio, 0, 5)
                                            : '' }}
                                    </td>

                                    <td>
                                        {{ $cita->motivo ?? 'Sin motivo' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $cita->estado ?? 'Sin estado' }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4"
                                        class="text-center text-secondary py-4">

                                        No hay próximas citas registradas.

                                    </td>
                                </tr>

                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card pc-card h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0">
                        Próximos seguimientos
                    </h5>

                    <a href="{{ url('/seguimientos') }}"
                       class="btn btn-sm btn-outline-secondary">
                        Ver todos
                    </a>
                </div>

                @forelse(($listaSeguimientos ?? []) as $seguimiento)

                    <div class="border-bottom py-3">

                        <div class="fw-semibold">
                            {{ $seguimiento->tratamiento?->paciente?->nombre_completo ?? 'Sin paciente' }}
                        </div>

                        <small class="d-block text-secondary mt-1">
                            Tratamiento:
                            {{ $seguimiento->tratamiento?->nombre ?? 'No disponible' }}
                        </small>

                        <small class="d-block text-secondary mt-1">
                            Próxima cita:
                            {{ $seguimiento->proxima_cita
                                ? \Carbon\Carbon::parse($seguimiento->proxima_cita)->format('d/m/Y H:i')
                                : 'Sin programar' }}
                        </small>

                        @if(!empty($seguimiento->evolucion))
                            <small class="d-block mt-2">
                                {{ \Illuminate\Support\Str::limit($seguimiento->evolucion, 100) }}
                            </small>
                        @endif

                    </div>

                @empty

                    <div class="text-center text-secondary py-4">

                        <i class="bi bi-clipboard2-check fs-1"></i>

                        <p class="mt-3 mb-0">
                            No hay seguimientos programados.
                        </p>

                    </div>

                @endforelse

            </div>
        </div>
    </div>

</div>

@endsection
