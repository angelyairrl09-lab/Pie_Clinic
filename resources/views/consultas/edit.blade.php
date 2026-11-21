@extends('layouts.app')
@section('title', 'Editar consulta')
@section('content')
<div class="mb-4"><h3 class="fw-bold">Editar consulta podológica</h3><p class="text-secondary">Actualiza la información de la atención.</p></div>
<div class="card pc-card"><div class="card-body"><form action="{{ route('consultas.update', $consulta) }}" method="POST">@csrf @method('PUT')
@include('consultas.form')
</form></div></div>
@endsection
