
@extends('layouts.app')

@section('title', 'Detalle de cita')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Detalle de cita</h3>
        <p class="text-secondary mb-0">Cita #{{ $cita->id_cita }}</p>
    </div>

    <a href="{{ route('citas.index') }}"
       class="btn btn-outline-secondary">
        Regresar
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card pc-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-4">Información de la cita</h5>

                <div class="row g-4">
                    <div class="col-md-6">
                        <small class="text-secondary">Paciente</small>
                        <div class="fw-semibold">
                            {{ $cita->paciente->nombre_completo }}
                        </div>
                        <small>{{ $cita->paciente->numero_paciente }}</small>
                    </div>

                    <div class="col-md-6">
                        <small class="text-secondary">Fecha</small>
                        <div>{{ $cita->fecha_cita->format('d/m/Y') }}</div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-secondary">Hora</small>
                        <div>{{ substr($cita->hora_inicio, 0, 5) }}</div>
                    </div>

                    <div class="col-md-6">
                        <small class="text-secondary">Duración</small>
                        <div>{{ $cita->duracion_minutos }} minutos</div>
                    </div>

                    <div class="col-12">
                        <small class="text-secondary">Motivo</small>
                        <div>{{ $cita->motivo }}</div>
                    </div>

                    <div class="col-12">
                        <small class="text-secondary">Observaciones</small>
                        <div style="white-space: pre-wrap;">{{ $cita->observaciones ?: 'Sin observaciones' }}</div>
                    </div>

                    <div class="col-12">
                        <small class="text-secondary">Registrado por</small>
                        <div>{{ $cita->usuarioRegistro?->name ?? 'No disponible' }}</div>
                    </div>
                </div>

                @if(in_array($cita->estado, ['PENDIENTE', 'CONFIRMADA']))
                    <a href="{{ route('citas.edit', $cita) }}"
                       class="btn btn-pc mt-4">
                        Reprogramar cita
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card pc-card">
            <div class="card-body">
                <h5 class="fw-semibold mb-4">Estado de la cita</h5>

                <p>
                    <strong>{{ str_replace('_', ' ', $cita->estado) }}</strong>
                </p>

                <form method="POST"
                      action="{{ route('citas.estado', $cita) }}">
                    @csrf
                    @method('PATCH')

                    <label class="form-label">Cambiar estado</label>

                    <select name="estado" class="form-select mb-3" required>
                        @foreach([
                            'PENDIENTE' => 'Pendiente',
                            'CONFIRMADA' => 'Confirmada',
                            'ATENDIDA' => 'Atendida',
                            'CANCELADA' => 'Cancelada',
                            'NO_ASISTIO' => 'No asistió'
                        ] as $valor => $texto)
                            <option value="{{ $valor }}"
                                @selected($cita->estado === $valor)>
                                {{ $texto }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-pc w-100">
                        Actualizar estado
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
