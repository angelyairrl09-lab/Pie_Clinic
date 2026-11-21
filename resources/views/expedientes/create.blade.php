
@extends('layouts.app')

@section('title', 'Nuevo expediente')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold">Registrar expediente clínico</h3>
    <p class="text-secondary">
        Registra los antecedentes clínicos del paciente.
    </p>
</div>

<div class="card pc-card">
    <div class="card-body">

        <form action="{{ route('expedientes.store') }}"
              method="POST">

            @csrf

            @include('expedientes.form')

        </form>

    </div>
</div>

@endsection
