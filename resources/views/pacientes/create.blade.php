
@extends('layouts.app')

@section('title', 'Nuevo paciente')

@section('content')

<div class="d-flex flex-wrap justify-content-between
            align-items-center gap-3 mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Nuevo paciente
        </h3>

        <p class="text-secondary mb-0">
            Completa la información del paciente.
        </p>

    </div>

    <a href="{{ route('pacientes.index') }}"
       class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-2"></i>
        Regresar

    </a>

</div>

<form method="POST"
      action="{{ route('pacientes.store') }}">

    @csrf

    @include('pacientes.form')

</form>

@endsection
