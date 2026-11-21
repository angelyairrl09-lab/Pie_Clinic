
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Revisa los siguientes datos:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card pc-card mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-4">Información de la cita</h5>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Paciente *</label>

                @if(isset($cita))
                    <input type="hidden" name="id_paciente"
                           value="{{ $cita->id_paciente }}">
                    <input type="text" class="form-control"
                           value="{{ $cita->paciente->nombre_completo }}"
                           disabled>
                @else
                    <select name="id_paciente" class="form-select" required>
                        <option value="">Seleccionar paciente</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id_paciente }}"
                                @selected(old('id_paciente', request('id_paciente')) == $paciente->id_paciente)>
                                {{ $paciente->numero_paciente }} -
                                {{ $paciente->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha de la cita *</label>
                <input type="date" name="fecha_cita" class="form-control"
                       value="{{ old('fecha_cita', isset($cita) ? $cita->fecha_cita->format('Y-m-d') : now()->toDateString()) }}"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Hora de inicio *</label>
                <input type="time" name="hora_inicio" class="form-control"
                       value="{{ old('hora_inicio', isset($cita) ? substr($cita->hora_inicio, 0, 5) : '') }}"
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Duración *</label>
                <select name="duracion_minutos" class="form-select" required>
                    @foreach([15, 30, 45, 60, 90, 120] as $minutos)
                        <option value="{{ $minutos }}"
                            @selected((int) old('duracion_minutos', $cita->duracion_minutos ?? 30) === $minutos)>
                            {{ $minutos }} minutos
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Motivo de la consulta *</label>
                <input type="text" name="motivo" class="form-control"
                       maxlength="200"
                       placeholder="Ej. Valoración podológica"
                       value="{{ old('motivo', $cita->motivo ?? '') }}"
                       required>
            </div>

            <div class="col-12">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" rows="4"
                          class="form-control"
                          maxlength="5000">{{ old('observaciones', $cita->observaciones ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('citas.index') }}"
       class="btn btn-outline-secondary">
        Cancelar
    </a>
    <button type="submit" class="btn btn-pc">
        {{ isset($cita) ? 'Actualizar cita' : 'Guardar cita' }}
    </button>
</div>
