@php
    $consulta = $consulta ?? null;
@endphp
<div class="row g-3">
<div class="col-md-8"><label class="form-label fw-semibold">Paciente / expediente clínico</label>
@if($consulta)
<input class="form-control" value="{{ $consulta->paciente?->nombre_completo ?? 'Sin paciente' }}" disabled>
@else
<select name="id_expediente" id="id_expediente" class="form-select @error('id_expediente') is-invalid @enderror" required>
<option value="">Seleccionar paciente con expediente</option>
@foreach($expedientes as $expediente)
<option value="{{ $expediente->id_expediente }}" @selected(old('id_expediente') == $expediente->id_expediente)>{{ $expediente->paciente?->nombre_completo ?? 'Sin paciente' }}</option>
@endforeach
</select>
@error('id_expediente')<div class="invalid-feedback">{{ $message }}</div>@enderror
@endif
</div>
<div class="col-md-4"><label class="form-label fw-semibold">Fecha de consulta</label><input type="date" name="fecha_consulta" class="form-control @error('fecha_consulta') is-invalid @enderror" value="{{ old('fecha_consulta', $consulta?->fecha_consulta?->format('Y-m-d') ?? date('Y-m-d')) }}" required>@error('fecha_consulta')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label class="form-label fw-semibold">Hora de consulta</label><input type="time" name="hora_consulta" class="form-control @error('hora_consulta') is-invalid @enderror" value="{{ old('hora_consulta', $consulta?->hora_consulta ? substr($consulta->hora_consulta, 0, 5) : '') }}">@error('hora_consulta')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label class="form-label fw-semibold">Próxima revisión</label><input type="date" name="proxima_revision" class="form-control @error('proxima_revision') is-invalid @enderror" value="{{ old('proxima_revision', $consulta?->proxima_revision?->format('Y-m-d')) }}">@error('proxima_revision')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-4"><label class="form-label fw-semibold">Tratamiento relacionado (opcional)</label><select name="id_tratamiento" class="form-select @error('id_tratamiento') is-invalid @enderror"><option value="">Sin tratamiento relacionado</option>
@foreach($tratamientos as $tratamiento)
<option value="{{ $tratamiento->id_tratamiento }}" @selected(old('id_tratamiento', $consulta?->id_tratamiento) == $tratamiento->id_tratamiento)>{{ $tratamiento->nombre }} — {{ $tratamiento->paciente?->nombre_completo ?? 'Paciente' }}</option>
@endforeach
</select><div class="form-text">Debe pertenecer al paciente seleccionado.</div>@error('id_tratamiento')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@foreach(['motivo_consulta'=>'Motivo de consulta', 'exploracion'=>'Exploración podológica', 'diagnostico'=>'Diagnóstico clínico', 'procedimiento'=>'Procedimiento realizado', 'indicaciones'=>'Indicaciones', 'observaciones'=>'Observaciones'] as $campo => $etiqueta)
<div class="col-md-6"><label for="{{ $campo }}" class="form-label fw-semibold">{{ $etiqueta }}</label><textarea name="{{ $campo }}" id="{{ $campo }}" rows="3" class="form-control @error($campo) is-invalid @enderror" @if($campo === 'motivo_consulta') required @endif>{{ old($campo, $consulta?->{$campo}) }}</textarea>@error($campo)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@endforeach
</div>
<hr class="my-4">
<div class="d-flex justify-content-end gap-2"><a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">Cancelar</a><button type="submit" class="btn btn-pc">{{ $consulta ? 'Actualizar consulta' : 'Guardar consulta' }}</button></div>
