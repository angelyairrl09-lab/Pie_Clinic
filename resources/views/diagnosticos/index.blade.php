
@extends('layouts.app')

@section('title', 'Diagnóstico e imágenes')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Diagnóstico e imágenes</h3>
        <p class="text-secondary mb-0">
            Historial de diagnósticos podológicos.
        </p>
    </div>

    <a href="{{ route('diagnosticos.create') }}" class="btn btn-pc">
        <i class="bi bi-plus-circle me-2"></i>
        Nuevo diagnóstico
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card pc-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('diagnosticos.index') }}">
            <div class="row g-3">
                <div class="col-md-9">
                    <input type="text" name="buscar"
                           value="{{ request('buscar') }}"
                           class="form-control"
                           placeholder="Buscar paciente o diagnóstico">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-pc w-100">Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card pc-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Diagnóstico</th>
                        <th>Fecha</th>
                        <th>Imágenes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($diagnosticos as $diagnostico)
                        <tr>
                            <td>
                                <strong>{{ $diagnostico->paciente->nombre_completo }}</strong>
                                <small class="d-block text-secondary">
                                    {{ $diagnostico->paciente->numero_paciente }}
                                </small>
                            </td>
                            <td>{{ $diagnostico->titulo }}</td>
                            <td>{{ $diagnostico->fecha_diagnostico->format('d/m/Y') }}</td>
                            <td>{{ $diagnostico->imagenes_count }}</td>
                            <td>
                                <a href="{{ route('diagnosticos.show', $diagnostico) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Ver
                                </a>
                                <a href="{{ route('diagnosticos.edit', $diagnostico) }}"
                                   class="btn btn-sm btn-pc">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
                                No hay diagnósticos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $diagnosticos->links() }}
    </div>
</div>

@endsection
