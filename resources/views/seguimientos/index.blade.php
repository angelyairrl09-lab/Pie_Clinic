@extends('layouts.app')
@section('title', 'Seguimientos')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h3 class="fw-bold mb-1">Seguimiento de tratamientos</h3><p class="text-secondary mb-0">Historial de evolución de los pacientes.</p></div>
    <a href="{{ route('seguimientos.create') }}" class="btn btn-pc">Nuevo seguimiento</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card pc-card"><div class="card-body">
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-8"><input class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar paciente, número o tratamiento"></div>
    <div class="col-md-4 d-flex gap-2"><button class="btn btn-pc" type="submit">Buscar</button><a class="btn btn-outline-secondary" href="{{ route('seguimientos.index') }}">Limpiar</a></div>
</form>
<div class="table-responsive"><table class="table table-hover align-middle"><thead><tr><th>Fecha</th><th>Paciente</th><th>Tratamiento</th><th>Próxima cita</th><th>Acciones</th></tr></thead><tbody>
@forelse($seguimientos as $s)
<tr><td>{{ $s->fecha_consulta?->format('d/m/Y') }}</td><td>{{ $s->tratamiento?->paciente?->nombre_completo ?? 'Sin paciente' }}</td><td>{{ $s->tratamiento?->nombre ?? 'No disponible' }}</td><td>{{ $s->proxima_cita?->format('d/m/Y H:i') ?? 'Sin definir' }}</td><td><a class="btn btn-sm btn-outline-secondary" href="{{ route('seguimientos.show', $s) }}">Ver</a> <a class="btn btn-sm btn-pc" href="{{ route('seguimientos.edit', $s) }}">Editar</a></td></tr>
@empty<tr><td colspan="5" class="text-center text-secondary">No hay seguimientos registrados.</td></tr>@endforelse
</tbody></table></div>{{ $seguimientos->links() }}
</div></div>
@endsection
