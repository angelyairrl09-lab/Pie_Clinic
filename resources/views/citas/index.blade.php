
@extends('layouts.app')

@section('title', 'Agenda de citas')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Agenda de citas</h3>
        <p class="text-secondary mb-0">
            Administración de citas del consultorio.
        </p>
    </div>

    <a href="{{ route('citas.create') }}" class="btn btn-pc">
        <i class="bi bi-calendar-plus me-2"></i>
        Nueva cita
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@php
    $tarjetas = [
        ['titulo' => 'Citas de hoy', 'total' => $citasHoy, 'icono' => 'calendar-day'],
        ['titulo' => 'Pendientes', 'total' => $pendientes, 'icono' => 'clock'],
        ['titulo' => 'Confirmadas', 'total' => $confirmadas, 'icono' => 'calendar-check'],
        ['titulo' => 'Atendidas', 'total' => $atendidas, 'icono' => 'check-circle']
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach($tarjetas as $tarjeta)
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card pc-card h-100">
                <div class="card-body">
                    <div class="pc-card-icon mb-3">
                        <i class="bi bi-{{ $tarjeta['icono'] }}"></i>
                    </div>
                    <small class="text-secondary">{{ $tarjeta['titulo'] }}</small>
                    <h2 class="fw-bold mt-2 mb-0">{{ $tarjeta['total'] }}</h2>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card pc-card mb-4">
    <div class="card-body">
        <form action="{{ route('citas.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Buscar paciente</label>
                    <input type="text" name="buscar" class="form-control"
                           value="{{ request('buscar') }}"
                           placeholder="Nombre o número de paciente">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control"
                           value="{{ request('fecha') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        @foreach([
                            'PENDIENTE' => 'Pendiente',
                            'CONFIRMADA' => 'Confirmada',
                            'ATENDIDA' => 'Atendida',
                            'CANCELADA' => 'Cancelada',
                            'NO_ASISTIO' => 'No asistió'
                        ] as $valor => $texto)
                            <option value="{{ $valor }}"
                                @selected(request('estado') === $valor)>
                                {{ $texto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-pc w-100">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card pc-card">
    <div class="card-body">
        <h5 class="fw-semibold mb-4">Listado de citas</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas as $cita)
                        <tr>
                            <td>
                                <strong>{{ $cita->paciente->nombre_completo }}</strong>
                                <small class="d-block text-secondary">
                                    {{ $cita->paciente->numero_paciente }}
                                </small>
                            </td>
                            <td>{{ $cita->fecha_cita->format('d/m/Y') }}</td>
                            <td>
                                {{ substr($cita->hora_inicio, 0, 5) }}
                                <small class="d-block text-secondary">
                                    {{ $cita->duracion_minutos }} minutos
                                </small>
                            </td>
                            <td>{{ $cita->motivo }}</td>
                            <td>
                                @php
                                    $color = match($cita->estado) {
                                        'CONFIRMADA' => 'primary',
                                        'ATENDIDA' => 'success',
                                        'CANCELADA' => 'danger',
                                        'NO_ASISTIO' => 'secondary',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $color }}">
                                    {{ str_replace('_', ' ', $cita->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('citas.show', $cita) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Ver
                                    </a>
                                    @if(in_array($cita->estado, ['PENDIENTE', 'CONFIRMADA']))
                                        <a href="{{ route('citas.edit', $cita) }}"
                                           class="btn btn-sm btn-pc">
                                            Editar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">
                                No hay citas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $citas->links() }}
    </div>
</div>

@endsection
