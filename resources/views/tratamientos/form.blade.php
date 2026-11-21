
@php
    $tratamiento = $tratamiento ?? null;
@endphp

<div class="row g-3">

    <div class="col-md-6">
        <label for="id_paciente" class="form-label fw-semibold">
            Paciente
        </label>

        <select name="id_paciente"
                id="id_paciente"
                class="form-select @error('id_paciente') is-invalid @enderror"
                required>

            <option value="">Seleccionar paciente</option>

            @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id_paciente }}"
                    @selected(old('id_paciente', $tratamiento?->id_paciente) == $paciente->id_paciente)>
                    {{ $paciente->nombre_completo }}
                </option>
            @endforeach

        </select>

        @error('id_paciente')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="nombre" class="form-label fw-semibold">
            Nombre del tratamiento
        </label>

        <input type="text"
               name="nombre"
               id="nombre"
               class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $tratamiento?->nombre) }}"
               maxlength="255"
               required>

        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="descripcion" class="form-label fw-semibold">
            Descripción
        </label>

        <textarea name="descripcion"
                  id="descripcion"
                  rows="3"
                  class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $tratamiento?->descripcion) }}</textarea>

        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="indicaciones" class="form-label fw-semibold">
            Indicaciones
        </label>

        <textarea name="indicaciones"
                  id="indicaciones"
                  rows="3"
                  class="form-control @error('indicaciones') is-invalid @enderror">{{ old('indicaciones', $tratamiento?->indicaciones) }}</textarea>

        @error('indicaciones')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="fecha_inicio" class="form-label fw-semibold">
            Fecha de inicio
        </label>

        <input type="date"
               name="fecha_inicio"
               id="fecha_inicio"
               class="form-control @error('fecha_inicio') is-invalid @enderror"
               value="{{ old('fecha_inicio', $tratamiento?->fecha_inicio?->format('Y-m-d')) }}"
               required>

        @error('fecha_inicio')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="fecha_fin" class="form-label fw-semibold">
            Fecha de finalización
        </label>

        <input type="date"
               name="fecha_fin"
               id="fecha_fin"
               class="form-control @error('fecha_fin') is-invalid @enderror"
               value="{{ old('fecha_fin', $tratamiento?->fecha_fin?->format('Y-m-d')) }}">

        @error('fecha_fin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="costo" class="form-label fw-semibold">
            Costo ($)
        </label>

        <input type="number"
               name="costo"
               id="costo"
               class="form-control @error('costo') is-invalid @enderror"
               min="0"
               step="0.01"
               value="{{ old('costo', $tratamiento?->costo) }}"
               placeholder="0.00">

        @error('costo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="estado" class="form-label fw-semibold">
            Estado del tratamiento
        </label>

        <select name="estado"
                id="estado"
                class="form-select @error('estado') is-invalid @enderror"
                required>

            @foreach([
                'PENDIENTE' => 'Pendiente',
                'ACTIVO' => 'Activo',
                'FINALIZADO' => 'Finalizado',
                'CANCELADO' => 'Cancelado'
            ] as $valor => $etiqueta)

                <option value="{{ $valor }}"
                    @selected(old('estado', $tratamiento?->estado ?? 'PENDIENTE') === $valor)>
                    {{ $etiqueta }}
                </option>

            @endforeach
        </select>

        @error('estado')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<hr class="my-4">

<div class="d-flex justify-content-end gap-2">

    <a href="{{ route('tratamientos.index') }}"
       class="btn btn-outline-secondary">
        Cancelar
    </a>

    <button type="submit" class="btn btn-pc">
        {{ $tratamiento ? 'Actualizar tratamiento' : 'Guardar tratamiento' }}
    </button>

</div>
