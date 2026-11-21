
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Revisa la información:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card pc-card mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-4">Información del paciente</h5>

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Paciente *</label>

                @if(isset($diagnostico))
                    <input type="hidden" name="id_paciente"
                           value="{{ $diagnostico->id_paciente }}">

                    <input class="form-control"
                           value="{{ $diagnostico->paciente->nombre_completo }}"
                           disabled>
                @else
                    <select name="id_paciente" class="form-select" required>
                        <option value="">Seleccionar paciente</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id_paciente }}"
                                @selected(old('id_paciente') == $paciente->id_paciente)>
                                {{ $paciente->numero_paciente }} -
                                {{ $paciente->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha del diagnóstico *</label>
                <input type="date" name="fecha_diagnostico"
                       class="form-control"
                       max="{{ now()->toDateString() }}"
                       value="{{ old('fecha_diagnostico', isset($diagnostico) ? $diagnostico->fecha_diagnostico->format('Y-m-d') : now()->toDateString()) }}"
                       required>
            </div>
        </div>
    </div>
</div>

<div class="card pc-card mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-4">Diagnóstico podológico</h5>

        <div class="mb-3">
            <label class="form-label">Nombre del diagnóstico *</label>
            <input type="text" name="titulo" class="form-control"
                   maxlength="200"
                   value="{{ old('titulo', $diagnostico->titulo ?? '') }}"
                   placeholder="Ej. Onicocriptosis"
                   required>
        </div>

        @foreach([
            'descripcion' => 'Descripción del diagnóstico *',
            'observaciones' => 'Observaciones',
            'recomendaciones' => 'Recomendaciones'
        ] as $campo => $etiqueta)
            <div class="mb-3">
                <label class="form-label">{{ $etiqueta }}</label>
                <textarea name="{{ $campo }}" rows="4"
                          class="form-control"
                          maxlength="10000"
                          @if($campo === 'descripcion') required @endif>{{ old($campo, $diagnostico->$campo ?? '') }}</textarea>
            </div>
        @endforeach
    </div>
</div>

<div class="card pc-card mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Fotografías clínicas</h5>

        <label for="imagenes" class="form-label">
            Seleccionar fotografías
        </label>

        <input type="file" name="imagenes[]" id="imagenes"
               class="form-control"
               accept="image/jpeg,image/png,image/webp"
               multiple>

        <small class="text-secondary d-block mt-2">
            Máximo 5 imágenes por carga, de hasta 5 MB cada una.
        </small>

        @if(isset($diagnostico) && $diagnostico->imagenes->isNotEmpty())
            <div class="row g-3 mt-3">
                @foreach($diagnostico->imagenes as $imagen)
                    <div class="col-6 col-md-3">
                        <img src="{{ route('diagnosticos.imagen', [$diagnostico, $imagen]) }}"
                             class="img-fluid rounded border"
                             alt="Fotografía clínica">
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('diagnosticos.index') }}"
       class="btn btn-outline-secondary">Cancelar</a>

    <button type="submit" class="btn btn-pc">
        {{ isset($diagnostico) ? 'Actualizar diagnóstico' : 'Guardar diagnóstico' }}
    </button>
</div>
