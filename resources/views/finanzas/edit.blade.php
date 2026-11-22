@extends('layouts.app')
@section('title', 'Editar movimiento')
@section('content')
<h3 class="fw-bold mb-4">Editar movimiento financiero</h3>
<div class="card pc-card"><div class="card-body">
<form method="POST" action="{{ route('finanzas.update', $movimiento) }}">
    @csrf
    @method('PUT')
    @include('finanzas._form')
</form>
</div></div>
@endsection
