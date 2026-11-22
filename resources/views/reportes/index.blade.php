@extends('layouts.app')
@section('title', 'Reportes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h3 class="fw-bold mb-1">Reportes</h3><p class="text-secondary mb-0">Consulta y exporta información de Pie Clinic.</p></div></div>
@if($error)<div class="alert alert-warning">{{ $error }}</div>@endif
@if(session('error'))<div class="alert alert-warning">{{ session('error') }}</div>@endif
<div class="card pc-card mb-4"><div class="card-body">
<form method="GET" action="{{ route('reportes.index') }}" class="row g-3 align-items-end">
<div class="col-md-4"><label class="form-label">Módulo</label><select class="form-select" name="tipo">
@foreach(['pacientes'=>'Pacientes','citas'=>'Citas','tratamientos'=>'Tratamientos','seguimientos'=>'Seguimientos','inventario'=>'Inventario','finanzas'=>'Finanzas'] as $clave=>$nombre)
<option value="{{ $clave }}" @selected($tipo===$clave)>{{ $nombre }}</option>
@endforeach
</select></div>
<div class="col-md-3"><label class="form-label">Desde</label><input type="date" class="form-control" name="desde" value="{{ request('desde') }}"></div>
<div class="col-md-3"><label class="form-label">Hasta</label><input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}"></div>
<div class="col-md-2"><button class="btn btn-pc w-100" type="submit">Consultar</button></div>
</form></div></div>
<div class="card pc-card"><div class="card-body">
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><h5 class="fw-semibold mb-0">Resultados ({{ $registros->count() }})</h5><div class="d-flex gap-2">
@if(!$error)<a class="btn btn-outline-secondary btn-sm" href="{{ route('reportes.csv', request()->query()) }}">Exportar CSV</a><a class="btn btn-pc btn-sm" href="{{ route('reportes.pdf', request()->query()) }}">Descargar PDF</a>@endif
</div></div>
<div class="table-responsive"><table class="table table-striped table-hover align-middle"><thead><tr>@foreach($columnas as $columna)<th>{{ str_replace('_', ' ', ucfirst($columna)) }}</th>@endforeach</tr></thead><tbody>
@forelse($registros as $registro)<tr>@foreach($columnas as $columna)<td>{{ \Illuminate\Support\Str::limit((string) ($registro->{$columna} ?? '—'), 100) }}</td>@endforeach</tr>
@empty<tr><td colspan="{{ max(1,count($columnas)) }}" class="text-center text-secondary py-4">No hay registros.</td></tr>@endforelse
</tbody></table></div><small class="text-secondary">Se muestran hasta 500 registros por consulta.</small>
</div></div>
@endsection
