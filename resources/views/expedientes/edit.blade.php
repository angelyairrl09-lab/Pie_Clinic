
@extends('layouts.app')

@section('title', 'Editar expediente')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold">Editar expediente clínico</h3>
    <p class="text-secondary">
        Actualiza los antecedentes clínicos del paciente.
    </p>
</div>

<div class="card pc-card">
    <div class="card-body">

        <form action="{{ route('expedientes.update', $expediente) }}"
              method="POST">

            @csrf
            @method('PUT')

            @include('expedientes.form')

        </form>

    </div>
</div>

@endsection
