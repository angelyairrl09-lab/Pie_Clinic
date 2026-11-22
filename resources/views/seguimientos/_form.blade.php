@php($editando = isset($seguimiento))
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
<div class="col-md-8"><label class="form-label">Tratamiento *</label><select name="id_tratamiento" class="form-select" required><option value="">Selecciona un tratamiento</option>@foreach($tratamientos as $t)<option value="{{ $t->id_tratamiento }}" @selected(old('id_tratamiento', $editando ? $seguimiento->id_tratamiento : ($seleccionado ?? '')) == $t->id_tratamiento)>{{ $t->paciente?->nombre_completo ?? 'Sin paciente' }} — {{ $t->nombre }}</option>@endforeach</select></div>
<div class="col-md-4"><label class="form-label">Fecha de consulta *</label><input type="date" name="fecha_consulta" class="form-control" value="{{ old('fecha_consulta', $editando ? $seguimiento->fecha_consulta?->format('Y-m-d') : now()->format('Y-m-d')) }}" required></div>
<div class="col-12"><label class="form-label">Observaciones</label><textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $editando ? $seguimiento->observaciones : '') }}</textarea></div>
<div class="col-12"><label class="form-label">Evolución</label><textarea name="evolucion" class="form-control" rows="3">{{ old('evolucion', $editando ? $seguimiento->evolucion : '') }}</textarea></div>
<div class="col-12"><label class="form-label">Avances</label><textarea name="avances" class="form-control" rows="3">{{ old('avances', $editando ? $seguimiento->avances : '') }}</textarea></div>
<div class="col-md-6"><label class="form-label">Próxima cita</label><input type="datetime-local" name="proxima_cita" class="form-control" value="{{ old('proxima_cita', $editando ? $seguimiento->proxima_cita?->format('Y-m-d\TH:i') : '') }}"></div>
<div class="col-md-6"><label class="form-label">Fotografías (hasta 6, máximo 5 MB cada una)</label><input type="file" name="fotografias[]" accept="image/jpeg,image/png,image/webp" multiple class="form-control"></div>
</div>
<div class="d-flex gap-2 mt-4"><button class="btn btn-pc" type="submit">{{ $editando ? 'Guardar cambios' : 'Registrar seguimiento' }}</button><a class="btn btn-outline-secondary" href="{{ route('seguimientos.index') }}">Cancelar</a></div>
