
@extends('layouts.app')

@section('title', 'Detalle del tratamiento')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">Detalle del tratamiento</h3>
        <p class="text-secondary mb-0">
            Información registrada del tratamiento.
        </p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('tratamientos.pdf', $tratamiento) }}"
           class="btn btn-outline-pc">
            Generar PDF
        </a>
        <a href="{{ route('tratamientos.edit', $tratamiento) }}"
           class="btn btn-pc">
            Editar tratamiento
        </a>
    </div>

</div>

<div class="card pc-card">
    <div class="card-body">

        <h5 class="fw-bold mb-4">
            {{ $tratamiento->nombre }}
        </h5>

        <div class="row g-4">

            <div class="col-md-6">
                <small class="text-secondary">Paciente</small>
                <div class="fw-semibold">
                    {{ $tratamiento->paciente?->nombre_completo ?? 'Sin paciente' }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-secondary">Estado</small>
                <div class="fw-semibold">
                    {{ $tratamiento->estado }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-secondary">Fecha de inicio</small>
                <div>
                    {{ $tratamiento->fecha_inicio->format('d/m/Y') }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-secondary">Fecha de finalización</small>
                <div>
                    {{ $tratamiento->fecha_fin?->format('d/m/Y') ?? 'Sin definir' }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-secondary">Costo</small>
                <div>
                    {{ $tratamiento->costo !== null
                        ? '$' . number_format($tratamiento->costo, 2) . ' MXN'
                        : 'Sin definir' }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-secondary">Registrado por</small>
                <div>
                    {{ $tratamiento->usuarioRegistro?->name ?? 'No disponible' }}
                </div>
            </div>

            <div class="col-12">
                <small class="text-secondary">Descripción</small>
                <div class="mt-1">
                    {!! nl2br(e($tratamiento->descripcion ?? 'Sin descripción')) !!}
                </div>
            </div>

            <div class="col-12">
                <small class="text-secondary">Indicaciones</small>
                <div class="mt-1">
                    {!! nl2br(e($tratamiento->indicaciones ?? 'Sin indicaciones')) !!}
                </div>
            </div>

        </div>

        <hr class="my-4">

        <a href="{{ route('tratamientos.index') }}"
           class="btn btn-outline-secondary">
            Volver al listado
        </a>

    </div>
</div>

@endsection
