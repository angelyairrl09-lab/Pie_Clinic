
@php
    $expediente = $expediente ?? null;
@endphp

<div class="row g-3">

    <div class="col-md-8">
        <label class="form-label fw-semibold">
            Paciente
        </label>

        @if($expediente)
            <input type="text"
                   class="form-control"
                   value="{{ $expediente->paciente?->nombre_completo }}"
                   disabled>
        @else
            <select name="id_paciente"
                    class="form-select @error('id_paciente') is-invalid @enderror"
                    required>

                <option value="">Seleccionar paciente</option>

                @foreach($pacientes as $paciente)
                    <option value="{{ $paciente->id_paciente }}"
                        @selected(old('id_paciente') == $paciente->id_paciente)>
                        {{ $paciente->nombre_completo }}
                    </option>
                @endforeach
            </select>

            @error('id_paciente')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        @endif
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Fecha de apertura
        </label>

        <input type="date"
               name="fecha_apertura"
               class="form-control @error('fecha_apertura') is-invalid @enderror"
               value="{{ old('fecha_apertura', $expediente?->fecha_apertura?->format('Y-m-d') ?? date('Y-m-d')) }}"
               required>

        @error('fecha_apertura')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Tipo de sangre
        </label>

        <select name="tipo_sangre" class="form-select">
            <option value="">No especificado</option>

            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $tipo)
                <option value="{{ $tipo }}"
                    @selected(old('tipo_sangre', $expediente?->tipo_sangre) === $tipo)>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <hr>
        <h5 class="fw-bold">Antecedentes clínicos</h5>
    </div>

    @foreach([
        'alergias' => 'Alergias',
        'enfermedades_cronicas' => 'Enfermedades crónicas',
        'medicamentos_actuales' => 'Medicamentos actuales',
        'antecedentes_personales' => 'Antecedentes personales',
        'antecedentes_familiares' => 'Antecedentes familiares',
        'antecedentes_quirurgicos' => 'Antecedentes quirúrgicos',
        'antecedentes_podologicos' => 'Antecedentes podológicos',
        'motivo_consulta_inicial' => 'Motivo de consulta inicial',
        'observaciones_generales' => 'Observaciones generales',
    ] as $campo => $etiqueta)

        <div class="col-md-6">
            <label for="{{ $campo }}" class="form-label fw-semibold">
                {{ $etiqueta }}
            </label>

            <textarea name="{{ $campo }}"
                      id="{{ $campo }}"
                      rows="3"
                      class="form-control @error($campo) is-invalid @enderror">{{ old($campo, $expediente?->{$campo}) }}</textarea>

            @error($campo)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    @endforeach

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

    <a href="{{ route('expedientes.index') }}"
       class="btn btn-outline-secondary">
        Cancelar
    </a>

    <button type="submit" class="btn btn-pc">
        {{ $expediente ? 'Actualizar expediente' : 'Guardar expediente' }}
    </button>

</div>
