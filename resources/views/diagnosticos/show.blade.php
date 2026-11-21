
@extends('layouts.app')

@section('title', 'Detalle del diagnóstico')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Detalle del diagnóstico</h3>

    <a href="{{ route('diagnosticos.edit', $diagnostico) }}"
       class="btn btn-pc">
        Editar diagnóstico
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card pc-card mb-4">
    <div class="card-body">
        <h5 class="fw-semibold">Paciente</h5>
        <p class="mb-1">
            {{ $diagnostico->paciente->nombre_completo }}
        </p>
        <small class="text-secondary">
            {{ $diagnostico->paciente->numero_paciente }}
        </small>

        <hr>

        <h5>{{ $diagnostico->titulo }}</h5>

        <p class="text-secondary">
            {{ $diagnostico->fecha_diagnostico->format('d/m/Y') }}
        </p>

        <h6 class="fw-bold">Descripción</h6>
        <p style="white-space: pre-wrap;">{{ $diagnostico->descripcion }}</p>

        <h6 class="fw-bold">Observaciones</h6>
        <p style="white-space: pre-wrap;">{{ $diagnostico->observaciones ?: 'Sin observaciones' }}</p>

        <h6 class="fw-bold">Recomendaciones</h6>
        <p style="white-space: pre-wrap;">{{ $diagnostico->recomendaciones ?: 'Sin recomendaciones' }}</p>
    </div>
</div>

<div class="card pc-card">
    <div class="card-body">
        <h5 class="fw-semibold mb-4">Imágenes del diagnóstico</h5>

        <div class="row g-3">
            @forelse($diagnostico->imagenes as $imagen)
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('diagnosticos.imagen', [$diagnostico, $imagen]) }}"
                       target="_blank" rel="noopener">
                        <img src="{{ route('diagnosticos.imagen', [$diagnostico, $imagen]) }}"
                             class="img-fluid rounded border"
                             alt="Fotografía clínica">
                    </a>
                    <small class="d-block text-secondary mt-2">
                        Imagen {{ $loop->iteration }}
                    </small>
                </div>
            @empty
                <div class="col-12 text-center text-secondary py-4">
                    No hay imágenes registradas.
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('diagnosticos.index') }}"
       class="btn btn-outline-secondary">Regresar</a>
</div>

@endsection
