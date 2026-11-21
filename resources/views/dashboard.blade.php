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
                'total' => $totalPacientes
            ],
            [
                'titulo' => 'Citas de hoy',
                'icono' => 'calendar-check',
                'total' => $citasHoy
            ],
            [
                'titulo' => 'Tratamientos activos',
                'icono' => 'bandaid',
                'total' => $tratamientosActivos
            ],
            [
                'titulo' => 'Seguimientos pendientes',
                'icono' => 'activity',
                'total' => $seguimientosPendientes
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
                                <th>Tratamiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($proximasCitas as $cita)

                                <tr>

                                    <td>
                                        {{ $cita->paciente?->nombre_completo ?? 'Sin paciente' }}
                                    </td>

                                    <td>
                                        {{ $cita->fecha_cita->format('d/m/Y') }}
                                        {{ substr($cita->hora_inicio, 0, 5) }}
                                    </td>

                                    <td>
                                        {{ $cita->motivo }}
                                    </td>

                                    <td>
                                        {{ $cita->estado }}
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

                <h5 class="fw-semibold mb-4">
                    Seguimientos pendientes
                </h5>

                @forelse($listaSeguimientos as $seguimiento)

                    <div class="border-bottom py-2">

                        <div class="fw-semibold">
                            {{ $seguimiento->paciente?->nombre_completo ?? 'Sin paciente' }}
                        </div>

                        <small class="text-secondary">
                            {{ $seguimiento->descripcion }}
                        </small>

                    </div>

                @empty

                    <div class="text-center text-secondary py-4">

                        <i class="bi bi-clipboard2-check fs-1"></i>

                        <p class="mt-3">
                            No hay seguimientos pendientes.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection
