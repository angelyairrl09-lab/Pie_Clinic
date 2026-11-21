@extends('layouts.app')
@section('title', 'Consultas podológicas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h3 class="fw-bold mb-1">Consultas podológicas</h3><p class="text-secondary mb-0">Historial de atención de pacientes.</p></div>
    <a href="{{ route('consultas.create') }}" class="btn btn-pc">Nueva consulta</a>
</div>
<div class="row g-4 mb-4"><div class="col-12 col-md-4"><div class="card pc-card"><div class="card-body"><small class="text-secondary">Consultas registradas</small><h2 class="fw-bold mt-2 mb-0">{{ $totalConsultas }}</h2></div></div></div></div>
<div class="card pc-card"><div class="card-body">
<form method="GET" action="{{ route('consultas.index') }}" class="row g-3 mb-4">
    <div class="col-md-9"><input name="buscar" class="form-control" placeholder="Buscar paciente o número" value="{{ request('buscar') }}"></div>
    <div class="col-md-3"><button class="btn btn-pc w-100" type="submit">Buscar</button></div>
</form>
<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Paciente</th><th>Fecha</th><th>Motivo</th><th>Próxima revisión</th><th>Acciones</th></tr></thead><tbody>
@forelse($consultas as $consulta)
<tr><td>{{ $consulta->paciente?->nombre_completo ?? 'Sin paciente' }}</td><td>{{ $consulta->fecha_consulta->format('d/m/Y') }}</td><td>{{ \Illuminate\Support\Str::limit($consulta->motivo_consulta, 70) }}</td><td>{{ $consulta->proxima_revision?->format('d/m/Y') ?? 'Sin definir' }}</td><td><div class="d-flex gap-2"><a class="btn btn-sm btn-outline-secondary" href="{{ route('consultas.show', $consulta) }}">Ver</a><a class="btn btn-sm btn-outline-pc" href="{{ route('consultas.edit', $consulta) }}">Editar</a></div></td></tr>
@empty
<tr><td colspan="5" class="text-center text-secondary py-4">No hay consultas registradas.</td></tr>
@endforelse
</tbody></table></div>
{{ $consultas->links() }}
</div></div>
@endsection
