@extends('layouts.app')
@section('title', 'Nuevo movimiento')
@section('content')
<h3 class="fw-bold mb-4">Nuevo movimiento financiero</h3>
<div class="card pc-card"><div class="card-body">
<form method="POST" action="{{ route('finanzas.store') }}">
    @csrf
    @include('finanzas._form')
</form>
</div></div>
@endsection
