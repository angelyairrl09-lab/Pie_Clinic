
@extends('layouts.app')

@section('title', 'Nueva cita')

@section('content')

<h3 class="fw-bold mb-4">Nueva cita</h3>

<form method="POST" action="{{ route('citas.store') }}">
    @csrf
    @include('citas.form')
</form>

@endsection
