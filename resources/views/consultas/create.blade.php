@extends('layouts.app')
@section('title', 'Nueva consulta')
@section('content')
<div class="mb-4"><h3 class="fw-bold">Registrar consulta podológica</h3><p class="text-secondary">Registra la atención del paciente.</p></div>
<div class="card pc-card"><div class="card-body"><form action="{{ route('consultas.store') }}" method="POST">@csrf
@include('consultas.form')
</form></div></div>
@endsection
