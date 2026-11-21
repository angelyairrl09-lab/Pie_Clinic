
@extends('layouts.app')

@section('title', 'Editar tratamiento')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold">Editar tratamiento</h3>
    <p class="text-secondary">
        Actualiza la información del tratamiento.
    </p>
</div>

<div class="card pc-card">
    <div class="card-body">

        <form action="{{ route('tratamientos.update', $tratamiento) }}"
              method="POST">

            @csrf
            @method('PUT')

            @include('tratamientos.form')

        </form>

    </div>
</div>

@endsection
