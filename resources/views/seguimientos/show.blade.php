@extends('layouts.app')
@section('title', 'Detalle del seguimiento')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><h3 class="fw-bold">Detalle del seguimiento</h3><div class="d-flex gap-2"><a class="btn btn-pc" href="{{ route('seguimientos.edit', $seguimiento) }}">Editar</a><a class="btn btn-outline-secondary" href="{{ route('seguimientos.index') }}">Volver</a></div></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card pc-card mb-4"><div class="card-body">
<h5 class="fw-bold">{{ $seguimiento->tratamiento?->paciente?->nombre_completo ?? 'Sin paciente' }}</h5>
<p class="text-secondary">{{ $seguimiento->tratamiento?->nombre }} — {{ $seguimiento->fecha_consulta?->format('d/m/Y') }}</p>
<div class="row g-4"><div class="col-md-6"><strong>Próxima cita</strong><p>{{ $seguimiento->proxima_cita?->format('d/m/Y H:i') ?? 'Sin definir' }}</p></div><div class="col-md-6"><strong>Registrado por</strong><p>{{ $seguimiento->usuarioRegistro?->name ?? 'No disponible' }}</p></div>
<div class="col-12"><strong>Observaciones</strong><p class="mt-1">{!! nl2br(e($seguimiento->observaciones ?: 'Sin observaciones')) !!}</p></div>
<div class="col-12"><strong>Evolución</strong><p class="mt-1">{!! nl2br(e($seguimiento->evolucion ?: 'Sin información')) !!}</p></div>
<div class="col-12"><strong>Avances</strong><p class="mt-1">{!! nl2br(e($seguimiento->avances ?: 'Sin información')) !!}</p></div></div>
@if($seguimiento->fotografias->isNotEmpty())<hr><h5>Fotografías</h5><div class="row g-3">@foreach($seguimiento->fotografias as $foto)<div class="col-6 col-md-3"><a href="{{ asset('storage/' . $foto->ruta) }}" target="_blank" rel="noopener"><img src="{{ asset('storage/' . $foto->ruta) }}" class="img-fluid rounded" alt="Fotografía del seguimiento"></a></div>@endforeach</div>@endif
</div></div>
<div class="card pc-card"><div class="card-body"><h5 class="fw-bold mb-3">Historial del tratamiento</h5><div class="table-responsive"><table class="table"><thead><tr><th>Fecha</th><th>Evolución</th><th>Acción</th></tr></thead><tbody>@foreach($historial as $item)<tr><td>{{ $item->fecha_consulta?->format('d/m/Y') }}</td><td>{{ \Illuminate\Support\Str::limit($item->evolucion ?: 'Sin información', 100) }}</td><td><a href="{{ route('seguimientos.show', $item) }}" class="btn btn-sm btn-outline-secondary">Ver</a></td></tr>@endforeach</tbody></table></div></div></div>
@endsection
