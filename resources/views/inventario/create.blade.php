@extends('layouts.app')
@section('title', 'Nuevo producto')
@section('content')
<h3 class="fw-bold mb-4">Nuevo producto</h3>
<div class="card pc-card"><div class="card-body"><form method="POST" action="{{ route('inventario.store') }}">
@csrf
@include('inventario._form')
</form></div></div>
@endsection
