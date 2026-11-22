<?php

namespace App\Http\Controllers;

use App\Models\MovimientoFinanciero;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinanzaController extends Controller
{
    private const TIPOS = ['INGRESO', 'EGRESO'];
    private const METODOS = ['EFECTIVO', 'TRANSFERENCIA', 'TARJETA', 'OTRO'];

    public function index(Request $request)
    {
        $filtros = $request->validate([
            'tipo' => ['nullable', Rule::in(self::TIPOS)],
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date|after_or_equal:desde',
            'buscar' => 'nullable|string|max:150',
        ]);

        $base = MovimientoFinanciero::query()
            ->when($filtros['tipo'] ?? null, fn ($q, $v) => $q->where('tipo', $v))
            ->when($filtros['desde'] ?? null, fn ($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($filtros['hasta'] ?? null, fn ($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->when($filtros['buscar'] ?? null, fn ($q, $v) => $q->where(function ($sub) use ($v) {
                $sub->where('concepto', 'like', "%{$v}%")
                    ->orWhere('categoria', 'like', "%{$v}%")
                    ->orWhere('referencia', 'like', "%{$v}%");
            }));

        $ingresos = (clone $base)->where('tipo', 'INGRESO')->sum('monto');
        $egresos = (clone $base)->where('tipo', 'EGRESO')->sum('monto');
        $movimientos = (clone $base)->with(['tratamiento', 'usuarioRegistro'])
            ->orderByDesc('fecha')->orderByDesc('id_movimiento')->paginate(15)->withQueryString();

        return view('finanzas.index', compact('movimientos', 'ingresos', 'egresos'));
    }

    public function create()
    {
        $tratamientos = Tratamiento::orderByDesc('id_tratamiento')->limit(300)->get(['id_tratamiento', 'nombre']);
        return view('finanzas.create', compact('tratamientos'));
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'tipo' => ['required', Rule::in(self::TIPOS)],
            'fecha' => 'required|date',
            'concepto' => 'required|string|max:200',
            'categoria' => 'required|string|max:100',
            'monto' => 'required|numeric|gt:0|max:9999999999.99',
            'metodo_pago' => ['required', Rule::in(self::METODOS)],
            'referencia' => 'nullable|string|max:120',
            'observaciones' => 'nullable|string|max:5000',
            'id_tratamiento' => 'nullable|exists:pc_tratamientos,id_tratamiento',
        ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $datos['registrado_por'] = $request->user()->id;
        MovimientoFinanciero::create($datos);
        return redirect()->route('finanzas.index')->with('success', 'Movimiento registrado correctamente.');
    }

    public function edit(MovimientoFinanciero $movimiento)
    {
        $tratamientos = Tratamiento::orderByDesc('id_tratamiento')->limit(300)->get(['id_tratamiento', 'nombre']);
        return view('finanzas.edit', compact('movimiento', 'tratamientos'));
    }

    public function update(Request $request, MovimientoFinanciero $movimiento)
    {
        $movimiento->update($this->validar($request));
        return redirect()->route('finanzas.index')->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(MovimientoFinanciero $movimiento)
    {
        $movimiento->delete();
        return redirect()->route('finanzas.index')->with('success', 'Movimiento eliminado correctamente.');
    }
}
