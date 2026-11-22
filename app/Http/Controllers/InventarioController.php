<?php

namespace App\Http\Controllers;

use App\Models\InventarioLote;
use App\Models\InventarioMovimiento;
use App\Models\InventarioProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InventarioController extends Controller
{
    private function reglas(?InventarioProducto $producto = null): array
    {
        return [
            'codigo' => ['required', 'string', 'max:60', Rule::unique('pc_inventario_productos', 'codigo')->ignore($producto?->id_producto, 'id_producto')],
            'nombre' => ['required', 'string', 'max:180'],
            'categoria' => ['required', Rule::in(['MEDICAMENTO', 'MATERIAL', 'PRODUCTO'])],
            'unidad' => ['required', 'string', 'max:40'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'precio_compra' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'precio_venta' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'descripcion' => ['nullable', 'string', 'max:10000'],
            'activo' => ['required', 'boolean'],
        ];
    }

    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:150'],
            'categoria' => ['nullable', Rule::in(['MEDICAMENTO', 'MATERIAL', 'PRODUCTO'])],
        ]);
        $productos = InventarioProducto::query()->withSum('lotes as existencia_total', 'existencia')
            ->when($filtros['buscar'] ?? null, fn ($q, $v) => $q->where(fn ($sub) => $sub->where('nombre', 'like', "%{$v}%")->orWhere('codigo', 'like', "%{$v}%")))
            ->when($filtros['categoria'] ?? null, fn ($q, $v) => $q->where('categoria', $v))
            ->orderBy('nombre')->paginate(15)->withQueryString();
        $caducidades = InventarioLote::with('producto')
            ->where('existencia', '>', 0)->whereNotNull('fecha_caducidad')
            ->whereDate('fecha_caducidad', '<=', now()->addDays(30)->toDateString())
            ->orderBy('fecha_caducidad')->limit(10)->get();
        return view('inventario.index', compact('productos', 'caducidades'));
    }

    public function create() { return view('inventario.create'); }

    public function store(Request $request)
    {
        $producto = InventarioProducto::create($request->validate($this->reglas()));
        return redirect()->route('inventario.show', $producto)->with('success', 'Producto registrado correctamente.');
    }

    public function show(InventarioProducto $producto)
    {
        $producto->load(['lotes' => fn ($q) => $q->orderByRaw('fecha_caducidad IS NULL')->orderBy('fecha_caducidad'), 'movimientos' => fn ($q) => $q->with('lote')->latest()->limit(30)]);
        return view('inventario.show', compact('producto'));
    }

    public function edit(InventarioProducto $producto) { return view('inventario.edit', compact('producto')); }

    public function update(Request $request, InventarioProducto $producto)
    {
        $producto->update($request->validate($this->reglas($producto)));
        return redirect()->route('inventario.show', $producto)->with('success', 'Producto actualizado correctamente.');
    }

    public function entrada(Request $request, InventarioProducto $producto)
    {
        $datos = $request->validate([
            'numero_lote' => ['required', 'string', 'max:100'],
            'fecha_caducidad' => ['nullable', 'date'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:100000000'],
            'motivo' => ['required', 'string', 'max:250'],
        ]);
        DB::transaction(function () use ($datos, $producto, $request) {
            // Bloquea el producto para serializar altas concurrentes del mismo lote.
            InventarioProducto::whereKey($producto->getKey())->lockForUpdate()->firstOrFail();
            $lote = InventarioLote::firstOrCreate(
                ['id_producto' => $producto->id_producto, 'numero_lote' => $datos['numero_lote'], 'fecha_caducidad' => $datos['fecha_caducidad'] ?? null],
                ['existencia' => 0]
            );
            $lote = InventarioLote::whereKey($lote->getKey())->lockForUpdate()->firstOrFail();
            if ($lote->existencia > 4294967295 - $datos['cantidad']) {
                throw ValidationException::withMessages(['cantidad' => 'La cantidad supera el máximo permitido.']);
            }
            $lote->increment('existencia', $datos['cantidad']);
            InventarioMovimiento::create([
                'id_producto' => $producto->id_producto, 'id_lote' => $lote->id_lote,
                'tipo' => 'ENTRADA', 'cantidad' => $datos['cantidad'], 'motivo' => $datos['motivo'],
                'registrado_por' => $request->user()?->id,
            ]);
        });
        return redirect()->route('inventario.show', $producto)->with('success', 'Entrada registrada correctamente.');
    }

    public function salida(Request $request, InventarioProducto $producto)
    {
        $datos = $request->validate([
            'id_lote' => ['required', 'integer', Rule::exists('pc_inventario_lotes', 'id_lote')->where('id_producto', $producto->id_producto)],
            'cantidad' => ['required', 'integer', 'min:1', 'max:100000000'],
            'motivo' => ['required', 'string', 'max:250'],
        ]);
        DB::transaction(function () use ($datos, $producto, $request) {
            $lote = InventarioLote::where('id_producto', $producto->id_producto)
                ->whereKey($datos['id_lote'])->lockForUpdate()->firstOrFail();
            if ($lote->existencia < $datos['cantidad']) {
                throw ValidationException::withMessages(['cantidad' => 'No hay existencias suficientes en el lote seleccionado.']);
            }
            $lote->decrement('existencia', $datos['cantidad']);
            InventarioMovimiento::create([
                'id_producto' => $producto->id_producto, 'id_lote' => $lote->id_lote,
                'tipo' => 'SALIDA', 'cantidad' => $datos['cantidad'], 'motivo' => $datos['motivo'],
                'registrado_por' => $request->user()?->id,
            ]);
        });
        return redirect()->route('inventario.show', $producto)->with('success', 'Salida registrada correctamente.');
    }
}
