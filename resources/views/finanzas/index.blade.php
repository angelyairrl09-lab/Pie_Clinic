@extends('layouts.app')
@section('title', 'Finanzas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h3 class="fw-bold mb-1">Finanzas</h3><p class="text-secondary mb-0">Ingresos y egresos del consultorio.</p></div>
    <a href="{{ route('finanzas.create') }}" class="btn btn-pc">Nuevo movimiento</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card pc-card"><div class="card-body"><small class="text-secondary">Ingresos (filtros actuales)</small><h3 class="fw-bold mt-2">${{ number_format($ingresos, 2) }}</h3></div></div></div>
    <div class="col-md-4"><div class="card pc-card"><div class="card-body"><small class="text-secondary">Egresos (filtros actuales)</small><h3 class="fw-bold mt-2">${{ number_format($egresos, 2) }}</h3></div></div></div>
    <div class="col-md-4"><div class="card pc-card"><div class="card-body"><small class="text-secondary">Balance (filtros actuales)</small><h3 class="fw-bold mt-2">${{ number_format($ingresos - $egresos, 2) }}</h3></div></div></div>
</div>
<div class="card pc-card mb-4"><div class="card-body">
    <form method="GET" action="{{ route('finanzas.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3"><label class="form-label">Buscar</label><input class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Concepto, categoría o referencia"></div>
        <div class="col-md-2"><label class="form-label">Tipo</label><select class="form-select" name="tipo"><option value="">Todos</option>@foreach(['INGRESO','EGRESO'] as $tipo)<option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ ucfirst(strtolower($tipo)) }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label">Desde</label><input type="date" class="form-control" name="desde" value="{{ request('desde') }}"></div>
        <div class="col-md-2"><label class="form-label">Hasta</label><input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}"></div>
        <div class="col-md-3"><button class="btn btn-pc" type="submit">Filtrar</button> <a href="{{ route('finanzas.index') }}" class="btn btn-outline-secondary">Limpiar</a></div>
    </form>
</div></div>
<div class="card pc-card"><div class="card-body"><div class="table-responsive"><table class="table align-middle">
    <thead><tr><th>Fecha</th><th>Tipo</th><th>Concepto</th><th>Categoría</th><th>Método</th><th class="text-end">Monto</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    @forelse($movimientos as $movimiento)
        <tr>
            <td>{{ $movimiento->fecha->format('d/m/Y') }}</td>
            <td><span class="badge {{ $movimiento->tipo === 'INGRESO' ? 'bg-success' : 'bg-danger' }}">{{ $movimiento->tipo }}</span></td>
            <td>{{ $movimiento->concepto }}</td><td>{{ $movimiento->categoria }}</td><td>{{ $movimiento->metodo_pago }}</td>
            <td class="text-end fw-semibold">${{ number_format($movimiento->monto, 2) }}</td>
            <td class="text-end"><div class="d-flex justify-content-end gap-2"><a href="{{ route('finanzas.edit', $movimiento) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                <form method="POST" action="{{ route('finanzas.destroy', $movimiento) }}" onsubmit="return confirm('¿Eliminar este movimiento?')">@csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form></div></td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-secondary py-4">No hay movimientos registrados.</td></tr>
    @endforelse
    </tbody>
</table></div><div class="mt-3">{{ $movimientos->links() }}</div></div></div>
@endsection
