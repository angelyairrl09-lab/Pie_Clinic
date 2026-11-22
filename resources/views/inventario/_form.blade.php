@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Código *</label><input class="form-control" name="codigo" maxlength="60" required value="{{ old('codigo', $producto->codigo ?? '') }}"></div>
    <div class="col-md-8"><label class="form-label">Nombre *</label><input class="form-control" name="nombre" maxlength="180" required value="{{ old('nombre', $producto->nombre ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Categoría *</label><select class="form-select" name="categoria" required>@foreach(['MEDICAMENTO','MATERIAL','PRODUCTO'] as $categoria)<option value="{{ $categoria }}" @selected(old('categoria', $producto->categoria ?? '') === $categoria)>{{ ucfirst(strtolower($categoria)) }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Unidad *</label><input class="form-control" name="unidad" maxlength="40" required value="{{ old('unidad', $producto->unidad ?? 'pieza') }}"></div>
    <div class="col-md-4"><label class="form-label">Stock mínimo *</label><input class="form-control" type="number" name="stock_minimo" min="0" required value="{{ old('stock_minimo', $producto->stock_minimo ?? 0) }}"></div>
    <div class="col-md-4"><label class="form-label">Precio de compra</label><input class="form-control" type="number" step="0.01" min="0" name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Precio de venta</label><input class="form-control" type="number" step="0.01" min="0" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Estado</label><select class="form-select" name="activo"><option value="1" @selected((string) old('activo', isset($producto) ? (int)$producto->activo : 1) === '1')>Activo</option><option value="0" @selected((string) old('activo', isset($producto) ? (int)$producto->activo : 1) === '0')>Inactivo</option></select></div>
    <div class="col-12"><label class="form-label">Descripción</label><textarea class="form-control" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea></div>
</div>
<div class="mt-4 d-flex gap-2"><button class="btn btn-pc" type="submit">Guardar producto</button><a class="btn btn-outline-secondary" href="{{ route('inventario.index') }}">Cancelar</a></div>
