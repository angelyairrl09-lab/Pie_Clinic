
@extends('layouts.app')

@section('title', 'Expedientes clínicos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Expedientes clínicos</h3>
        <p class="text-secondary mb-0">
            Historial clínico general de los pacientes.
        </p>
    </div>

    <a href="{{ route('expedientes.create') }}"
       class="btn btn-pc">
        Nuevo expediente
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card pc-card">
            <div class="card-body">
                <small class="text-secondary">
                    Expedientes registrados
                </small>
                <h2 class="fw-bold mt-2 mb-0">
                    {{ $totalExpedientes }}
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="card pc-card">
    <div class="card-body">

        <form method="GET"
              action="{{ route('expedientes.index') }}"
              class="row g-3 mb-4">

            <div class="col-md-9">
                <input type="text"
                       name="buscar"
                       class="form-control"
                       placeholder="Buscar paciente o número de paciente"
                       value="{{ request('buscar') }}">
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-pc w-100">
                    Buscar
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Número</th>
                        <th>Fecha de apertura</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($expedientes as $expediente)
                        <tr>
                            <td>
                                {{ $expediente->paciente?->nombre_completo ?? 'Sin paciente' }}
                            </td>

                            <td>
                                {{ $expediente->paciente?->numero_paciente ?? 'N/D' }}
                            </td>

                            <td>
                                {{ $expediente->fecha_apertura->format('d/m/Y') }}
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('expedientes.show', $expediente) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Ver
                                    </a>

                                    <a href="{{ route('expedientes.edit', $expediente) }}"
                                       class="btn btn-sm btn-outline-pc">
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                class="text-center text-secondary py-4">
                                No hay expedientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $expedientes->links() }}

    </div>
</div>

@endsection
