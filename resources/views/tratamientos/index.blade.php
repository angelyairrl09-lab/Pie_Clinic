@extends('layouts.app')
@section('title', 'Tratamientos')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tratamientos</h3>
        <p class="text-secondary mb-0">
            Administración de tratamientos de pacientes.
        </p>
    </div>

    <a href="{{ route('tratamientos.create') }}"
       class="btn btn-pc">
        Nuevo tratamiento
    </a>
</div>

<div class="row g-4 mb-4">

    @foreach([
        ['titulo' => 'Registrados', 'total' => $totalTratamientos],
        ['titulo' => 'Activos', 'total' => $tratamientosActivos],
        ['titulo' => 'Pendientes', 'total' => $tratamientosPendientes],
        ['titulo' => 'Finalizados', 'total' => $tratamientosFinalizados],
    ] as $tarjeta)

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card pc-card h-100">
                <div class="card-body">
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

</div>

<div class="card pc-card">
    <div class="card-body">

        <form method="GET"
              action="{{ route('tratamientos.index') }}"
              class="row g-3 mb-4">

            <div class="col-md-7">
                <input type="text"
                       name="buscar"
                       class="form-control"
                       placeholder="Buscar paciente o tratamiento"
                       value="{{ request('buscar') }}">
            </div>

            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>

                    @foreach(['PENDIENTE', 'ACTIVO', 'FINALIZADO', 'CANCELADO'] as $estado)
                        <option value="{{ $estado }}"
                            @selected(request('estado') === $estado)>
                            {{ ucfirst(strtolower($estado)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
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
                        <th>Tratamiento</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Costo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tratamientos as $tratamiento)
                        <tr>
                            <td>
                                {{ $tratamiento->paciente?->nombre_completo ?? 'Sin paciente' }}
                            </td>

                            <td>
                                {{ $tratamiento->nombre }}
                            </td>

                            <td>
                                {{ $tratamiento->fecha_inicio->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $tratamiento->fecha_fin?->format('d/m/Y') ?? 'Sin definir' }}
                            </td>

                            <td>
                                {{ $tratamiento->costo !== null
                                    ? '$' . number_format($tratamiento->costo, 2)
                                    : 'Sin definir' }}
                            </td>

                            <td>
                                <span class="badge text-bg-light border">
                                    {{ $tratamiento->estado }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('tratamientos.show', $tratamiento) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Ver
                                    </a>

                                    <a href="{{ route('tratamientos.edit', $tratamiento) }}"
                                       class="btn btn-sm btn-outline-pc">
                                        Editar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="text-center text-secondary py-4">
                                No hay tratamientos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{ $tratamientos->links() }}

    </div>
</div>

@endsection
