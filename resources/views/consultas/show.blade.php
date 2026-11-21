@extends('layouts.app')
@section('title', 'Detalle de consulta')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h3 class="fw-bold mb-1">Detalle de consulta</h3><p class="text-secondary mb-0">Atención podológica registrada.</p></div><a href="{{ route('consultas.edit', $consulta) }}" class="btn btn-pc">Editar consulta</a></div>
<div class="card pc-card"><div class="card-body"><h5 class="fw-bold mb-4">{{ $consulta->paciente?->nombre_completo ?? 'Sin paciente' }}</h5>
<div class="row g-4"><div class="col-md-4"><small class="text-secondary">Fecha</small><div class="fw-semibold">{{ $consulta->fecha_consulta->format('d/m/Y') }}</div></div><div class="col-md-4"><small class="text-secondary">Hora</small><div class="fw-semibold">{{ $consulta->hora_consulta ? substr($consulta->hora_consulta, 0, 5) : 'Sin definir' }}</div></div><div class="col-md-4"><small class="text-secondary">Próxima revisión</small><div class="fw-semibold">{{ $consulta->proxima_revision?->format('d/m/Y') ?? 'Sin definir' }}</div></div><div class="col-md-6"><small class="text-secondary">Tratamiento relacionado</small><div class="fw-semibold">{{ $consulta->tratamiento?->nombre ?? 'Sin tratamiento' }}</div></div><div class="col-md-6"><small class="text-secondary">Registrado por</small><div class="fw-semibold">{{ $consulta->usuarioRegistro?->name ?? 'No disponible' }}</div></div></div>
<hr class="my-4"><div class="row g-4">
@foreach(['motivo_consulta'=>'Motivo de consulta', 'exploracion'=>'Exploración podológica', 'diagnostico'=>'Diagnóstico clínico', 'procedimiento'=>'Procedimiento realizado', 'indicaciones'=>'Indicaciones', 'observaciones'=>'Observaciones'] as $campo => $etiqueta)
<div class="col-md-6"><small class="text-secondary">{{ $etiqueta }}</small><div class="mt-1">{!! nl2br(e($consulta->{$campo} ?: 'Sin información')) !!}</div></div>
@endforeach
</div><hr class="my-4"><a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">Volver al listado</a></div></div>
@endsection
