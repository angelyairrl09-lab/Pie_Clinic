
@extends('layouts.app')

@section('title', 'Detalle del expediente')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Expediente clínico</h3>
        <p class="text-secondary mb-0">
            Información clínica del paciente.
        </p>
    </div>

    <a href="{{ route('expedientes.edit', $expediente) }}"
       class="btn btn-pc">
        Editar expediente
    </a>
</div>

<div class="card pc-card">
    <div class="card-body">

        <h5 class="fw-bold mb-4">
            {{ $expediente->paciente?->nombre_completo ?? 'Sin paciente' }}
        </h5>

        <div class="row g-4">

            <div class="col-md-4">
                <small class="text-secondary">
                    Número de paciente
                </small>
                <div class="fw-semibold">
                    {{ $expediente->paciente?->numero_paciente ?? 'N/D' }}
                </div>
            </div>

            <div class="col-md-4">
                <small class="text-secondary">
                    Fecha de apertura
                </small>
                <div class="fw-semibold">
                    {{ $expediente->fecha_apertura->format('d/m/Y') }}
                </div>
            </div>

            <div class="col-md-4">
                <small class="text-secondary">
                    Tipo de sangre
                </small>
                <div class="fw-semibold">
                    {{ $expediente->tipo_sangre ?: 'No especificado' }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-secondary">
                    Registrado por
                </small>
                <div class="fw-semibold">
                    {{ $expediente->usuarioRegistro?->name ?? 'No disponible' }}
                </div>
            </div>

        </div>

        <hr class="my-4">

        <h5 class="fw-bold mb-4">Antecedentes clínicos</h5>

        <div class="row g-4">
            @foreach([
                'alergias' => 'Alergias',
                'enfermedades_cronicas' => 'Enfermedades crónicas',
                'medicamentos_actuales' => 'Medicamentos actuales',
                'antecedentes_personales' => 'Antecedentes personales',
                'antecedentes_familiares' => 'Antecedentes familiares',
                'antecedentes_quirurgicos' => 'Antecedentes quirúrgicos',
                'antecedentes_podologicos' => 'Antecedentes podológicos',
                'motivo_consulta_inicial' => 'Motivo de consulta inicial',
                'observaciones_generales' => 'Observaciones generales',
            ] as $campo => $etiqueta)

                <div class="col-md-6">
                    <small class="text-secondary">
                        {{ $etiqueta }}
                    </small>

                    <div class="mt-1">
                        {!! nl2br(e($expediente->{$campo} ?: 'Sin información')) !!}
                    </div>
                </div>

            @endforeach
        </div>

        <hr class="my-4">

        <a href="{{ route('expedientes.index') }}"
           class="btn btn-outline-secondary">
            Volver al listado
        </a>

    </div>
</div>

@endsection
