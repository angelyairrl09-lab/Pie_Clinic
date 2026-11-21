
@extends('layouts.app')

@section('title', 'Información del paciente')

@section('content')

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif

<div class="d-flex flex-wrap justify-content-between
            align-items-center gap-3 mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Información del paciente
        </h3>

        <p class="text-secondary mb-0">
            {{ $paciente->numero_paciente }}
        </p>

    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('pacientes.edit', $paciente) }}"
           class="btn btn-pc">

            <i class="bi bi-pencil-square me-2"></i>
            Editar

        </a>

        <a href="{{ route('pacientes.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-2"></i>
            Regresar

        </a>

    </div>

</div>

<div class="row g-4">

    <div class="col-lg-4">

        <div class="card pc-card mb-4">

            <div class="card-body text-center py-4">

                <div class="pc-card-icon mx-auto mb-3"
                     style="width:85px;height:85px;font-size:40px;">

                    <i class="bi bi-person"></i>

                </div>

                <h5 class="fw-bold">
                    {{ $paciente->nombre_completo }}
                </h5>

                <p class="text-secondary">
                    {{ $paciente->numero_paciente }}
                </p>

                <span class="badge {{ $paciente->activo ? 'text-bg-success' : 'text-bg-secondary' }}">

                    {{ $paciente->activo ? 'Activo' : 'Inactivo' }}

                </span>

                <hr>

                <p class="mb-2">

                    <i class="bi bi-telephone me-2"></i>

                    {{ $paciente->telefono ?? 'Sin teléfono' }}

                </p>

                <p class="mb-0 text-break">

                    <i class="bi bi-envelope me-2"></i>

                    {{ $paciente->correo ?? 'Sin correo' }}

                </p>

            </div>

        </div>

        <div class="card pc-card">

            <div class="card-body">

                <h5 class="fw-semibold mb-4">
                    Información del registro
                </h5>

                <small class="text-secondary">Fecha de registro</small>

                <p>
                    {{ $paciente->created_at->format('d/m/Y H:i') }}
                </p>

                <small class="text-secondary">Registrado por</small>

                <p class="mb-0">
                    {{ $paciente->usuarioRegistro?->name ?? 'No disponible' }}
                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="card pc-card mb-4">

            <div class="card-body">

                <h5 class="fw-semibold mb-4">
                    Datos personales
                </h5>

                <div class="row g-4">

                    <div class="col-md-6">

                        <small class="text-secondary">
                            Nombre completo
                        </small>

                        <div>{{ $paciente->nombre_completo }}</div>

                    </div>

                    <div class="col-md-6">

                        <small class="text-secondary">
                            Fecha de nacimiento
                        </small>

                        <div>
                            {{ $paciente->fecha_nacimiento->format('d/m/Y') }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <small class="text-secondary">Edad</small>

                        <div>{{ $paciente->edad }} años</div>

                    </div>

                    <div class="col-md-4">

                        <small class="text-secondary">Sexo</small>

                        <div>
                            {{ ucfirst(strtolower(str_replace('_', ' ', $paciente->sexo))) }}
                        </div>

                    </div>

                    <div class="col-md-4">

                        <small class="text-secondary">Ocupación</small>

                        <div>{{ $paciente->ocupacion ?? 'No registrada' }}</div>

                    </div>

                    <div class="col-12">

                        <small class="text-secondary">Domicilio</small>

                        <div>
                            {{ $paciente->domicilio ?: 'No registrado' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card pc-card mb-4">

            <div class="card-body">

                <h5 class="fw-semibold mb-4">
                    Contacto de emergencia
                </h5>

                <div class="row g-3">

                    <div class="col-md-6">

                        <small class="text-secondary">
                            Nombre
                        </small>

                        <div>
                            {{ $paciente->contacto_emergencia ?? 'No registrado' }}
                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-secondary">
                            Teléfono
                        </small>

                        <div>
                            {{ $paciente->telefono_emergencia ?? 'No registrado' }}
                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-secondary">
                            Parentesco
                        </small>

                        <div>
                            {{ $paciente->parentesco_emergencia ?? 'No registrado' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card pc-card">

            <div class="card-body">

                <h5 class="fw-semibold mb-3">
                    Observaciones generales
                </h5>

                <div style="white-space: pre-wrap;">{{ $paciente->observaciones ?? 'Sin observaciones.' }}</div>

            </div>

        </div>

    </div>

</div>

@endsection
