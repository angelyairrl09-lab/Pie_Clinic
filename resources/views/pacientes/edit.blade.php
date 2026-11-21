
@extends('layouts.app')

@section('title', 'Editar paciente')

@section('content')

<div class="d-flex flex-wrap justify-content-between
            align-items-center gap-3 mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Editar paciente
        </h3>

        <p class="text-secondary mb-0">
            {{ $paciente->numero_paciente }} |
            {{ $paciente->nombre_completo }}
        </p>

    </div>

    <a href="{{ route('pacientes.show', $paciente) }}"
       class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-2"></i>
        Regresar

    </a>

</div>

<form method="POST"
      action="{{ route('pacientes.update', $paciente) }}">

    @csrf
    @method('PUT')

    @include('pacientes.form')

</form>

@endsection
