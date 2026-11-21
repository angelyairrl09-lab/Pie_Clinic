@extends('layouts.app')

@section('title', 'Nuevo tratamiento')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold">Registrar tratamiento</h3>
    <p class="text-secondary">
        Registra un tratamiento para un paciente.
    </p>
</div>

<div class="card pc-card">
    <div class="card-body">

        <form action="{{ route('tratamientos.store') }}"
              method="POST">

            @csrf

            @include('tratamientos.form')

        </form>

    </div>
</div>

@endsection
