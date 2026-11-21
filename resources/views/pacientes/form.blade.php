
@if($errors->any())

    <div class="alert alert-danger">
        Revisa los campos del formulario.
    </div>

@endif

@php

    $camposPersonales = [
        ['nombre', 'Nombre(s)', 'text', true, 4],
        ['apellido_paterno', 'Apellido paterno', 'text', true, 4],
        ['apellido_materno', 'Apellido materno', 'text', false, 4],
        ['fecha_nacimiento', 'Fecha de nacimiento', 'date', true, 4],
        ['telefono', 'Teléfono', 'tel', false, 4],
        ['correo', 'Correo electrónico', 'email', false, 4],
        ['ocupacion', 'Ocupación', 'text', false, 8]
    ];

    $camposDomicilio = [
        ['calle', 'Calle', 'text', false, 8],
        ['numero_exterior', 'Número exterior', 'text', false, 2],
        ['numero_interior', 'Número interior', 'text', false, 2],
        ['colonia', 'Colonia', 'text', false, 6],
        ['municipio', 'Municipio', 'text', false, 6],
        ['estado', 'Estado', 'text', false, 6],
        ['codigo_postal', 'Código postal', 'text', false, 6]
    ];

    $camposEmergencia = [
        ['contacto_emergencia', 'Nombre del contacto', 'text', false, 6],
        ['telefono_emergencia', 'Teléfono', 'tel', false, 3],
        ['parentesco_emergencia', 'Parentesco', 'text', false, 3]
    ];

    $secciones = [
        [
            'titulo' => 'Datos personales',
            'icono' => 'person-vcard',
            'campos' => $camposPersonales
        ],
        [
            'titulo' => 'Domicilio',
            'icono' => 'geo-alt',
            'campos' => $camposDomicilio
        ],
        [
            'titulo' => 'Contacto de emergencia',
            'icono' => 'telephone',
            'campos' => $camposEmergencia
        ]
    ];

@endphp

@foreach($secciones as $seccion)

    <div class="card pc-card mb-4">

        <div class="card-body">

            <h5 class="fw-semibold mb-4">

                <i class="bi bi-{{ $seccion['icono'] }} me-2"></i>

                {{ $seccion['titulo'] }}

            </h5>

            <div class="row g-3">

                @foreach($seccion['campos'] as [$campo, $titulo, $tipo, $requerido, $columna])

                    <div class="col-md-{{ $columna }}">

                        <label for="{{ $campo }}" class="form-label">

                            {{ $titulo }}

                            @if($requerido)
                                <span class="text-danger">*</span>
                            @endif

                        </label>

                        <input type="{{ $tipo }}"
                               id="{{ $campo }}"
                               name="{{ $campo }}"
                               class="form-control @error($campo) is-invalid @enderror"
                               value="{{ old($campo, $campo === 'fecha_nacimiento' ? ($paciente->fecha_nacimiento ?? null)?->format('Y-m-d') : ($paciente->$campo ?? '')) }}"
                               @if($campo === 'fecha_nacimiento')
                                   max="{{ now()->toDateString() }}"
                               @endif
                               @if($campo === 'codigo_postal')
                                   maxlength="5"
                                   inputmode="numeric"
                               @endif
                               @if($requerido) required @endif>

                        @error($campo)

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                @endforeach

                @if($seccion['titulo'] === 'Datos personales')

                    <div class="col-md-4">

                        <label for="sexo" class="form-label">
                            Sexo <span class="text-danger">*</span>
                        </label>

                        <select id="sexo"
                                name="sexo"
                                class="form-select @error('sexo') is-invalid @enderror"
                                required>

                            <option value="">Seleccionar</option>

                            @foreach([
                                'MASCULINO' => 'Masculino',
                                'FEMENINO' => 'Femenino',
                                'OTRO' => 'Otro',
                                'NO_ESPECIFICADO' => 'No especificado'
                            ] as $valor => $texto)

                                <option value="{{ $valor }}"
                                    @selected(old('sexo', $paciente->sexo ?? '') === $valor)>

                                    {{ $texto }}

                                </option>

                            @endforeach

                        </select>

                        @error('sexo')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                @endif

            </div>

        </div>

    </div>

@endforeach

<div class="card pc-card mb-4">

    <div class="card-body">

        <h5 class="fw-semibold mb-3">
            Observaciones generales
        </h5>

        <label for="observaciones" class="form-label">
            Información adicional
        </label>

        <textarea name="observaciones"
                  id="observaciones"
                  rows="4"
                  maxlength="5000"
                  class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $paciente->observaciones ?? '') }}</textarea>

        @error('observaciones')

            <div class="invalid-feedback">
                {{ $message }}
            </div>

        @enderror

    </div>

</div>

<div class="d-flex justify-content-end gap-2">

    <a href="{{ route('pacientes.index') }}"
       class="btn btn-outline-secondary">

        Cancelar

    </a>

    <button type="submit" class="btn btn-pc">

        <i class="bi bi-floppy me-2"></i>

        {{ isset($paciente) ? 'Actualizar paciente' : 'Guardar paciente' }}

    </button>

</div>
