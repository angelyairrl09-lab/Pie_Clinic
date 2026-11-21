
@extends('layouts.app')

@section('title', 'Editar cita')

@section('content')

<h3 class="fw-bold mb-4">Editar cita</h3>

<form method="POST" action="{{ route('citas.update', $cita) }}">
    @csrf
    @method('PUT')
    @include('citas.form')
</form>

@endsection
