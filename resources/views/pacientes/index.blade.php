
@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')

<div class="d-flex flex-wrap justify-content-between
            align-items-center gap-3 mb-4">

    <div>
        <h3 class="fw-bold mb-1">Pacientes</h3>

        <p class="text-secondary mb-0">
            Administración de pacientes de la clínica.
        </p>
    </div>

    <a href="{{ route('pacientes.create') }}"
       class="btn btn-pc">

        <i class="bi bi-person-plus me-2"></i>
        Nuevo paciente

    </a>

</div>

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif

@php
    $indicadores = [
        [
            'titulo' => 'Total de pacientes',
            'total' => $totalPacientes,
            'icono' => 'people'
        ],
        [
            'titulo' => 'Pacientes activos',
            'total' => $totalActivos,
            'icono' => 'person-check'
        ],
        [
            'titulo' => 'Pacientes inactivos',
            'total' => $totalInactivos,
            'icono' => 'person-dash'
        ],
        [
            'titulo' => 'Nuevos este mes',
            'total' => $totalNuevos,
            'icono' => 'person-plus'
        ]
    ];
@endphp

<div class="row g-3 mb-4">

    @foreach($indicadores as $indicador)

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card pc-card h-100">

                <div class="card-body">

                    <div class="pc-card-icon mb-3">
                        <i class="bi bi-{{ $indicador['icono'] }}"></i>
                    </div>

                    <small class="text-secondary">
                        {{ $indicador['titulo'] }}
                    </small>

                    <h2 class="fw-bold mt-2 mb-0">
                        {{ $indicador['total'] }}
                    </h2>

                </div>

            </div>

        </div>

    @endforeach

</div>

<div class="card pc-card mb-4">

    <div class="card-body">

        <form method="GET"
              action="{{ route('pacientes.index') }}">

            <div class="row g-3 align-items-end">

                <div class="col-md-6">

                    <label class="form-label">
                        Buscar paciente
                    </label>

                    <input type="text"
                           name="buscar"
                           class="form-control"
                           value="{{ request('buscar') }}"
                           placeholder="Nombre, número o teléfono">

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Estado
                    </label>

                    <select name="estado" class="form-select">

                        <option value="">Todos</option>

                        <option value="1"
                            @selected(request('estado') === '1')>
                            Activos
                        </option>

                        <option value="0"
                            @selected(request('estado') === '0')>
                            Inactivos
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <div class="d-flex gap-2">

                        <button class="btn btn-pc flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            Buscar
                        </button>

                        <a href="{{ route('pacientes.index') }}"
                           class="btn btn-outline-secondary"
                           title="Limpiar filtros"
                           aria-label="Limpiar filtros">

                            <i class="bi bi-arrow-clockwise"></i>

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="card pc-card">

    <div class="card-body">

        <h5 class="fw-semibold mb-4">
            Listado de pacientes
        </h5>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pacientes as $paciente)

                        <tr>

                            <td class="fw-semibold">
                                {{ $paciente->numero_paciente }}
                            </td>

                            <td>

                                {{ $paciente->nombre_completo }}

                                <small class="d-block text-secondary">
                                    {{ $paciente->correo }}
                                </small>

                            </td>

                            <td>
                                {{ $paciente->edad }} años
                            </td>

                            <td>
                                {{ $paciente->telefono ?? 'Sin teléfono' }}
                            </td>

                            <td>

                                @if($paciente->activo)

                                    <span class="badge text-bg-success">
                                        Activo
                                    </span>

                                @else

                                    <span class="badge text-bg-secondary">
                                        Inactivo
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('pacientes.show', $paciente) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Consultar"
                                       aria-label="Consultar paciente">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a href="{{ route('pacientes.edit', $paciente) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Editar"
                                       aria-label="Editar paciente">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>

                                    <form method="POST"
                                          action="{{ route('pacientes.estado', $paciente) }}"
                                          onsubmit="return confirm('¿Deseas cambiar el estado del paciente?')">

                                        @csrf
                                        @method('PATCH')

                                        <input type="hidden"
                                               name="activo"
                                               value="{{ $paciente->activo ? 0 : 1 }}">

                                        <button type="submit"
                                                class="btn btn-sm {{ $paciente->activo ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                title="{{ $paciente->activo ? 'Desactivar' : 'Activar' }}"
                                                aria-label="{{ $paciente->activo ? 'Desactivar paciente' : 'Activar paciente' }}">

                                            <i class="bi {{ $paciente->activo ? 'bi-person-dash' : 'bi-person-check' }}"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">

                                <i class="bi bi-people fs-1 text-secondary"></i>

                                <p class="text-secondary mt-3 mb-0">
                                    No se encontraron pacientes.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{ $pacientes->links() }}

    </div>

</div>

@endsection
