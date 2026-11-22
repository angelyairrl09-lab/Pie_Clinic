<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioMovimiento extends Model
{
    protected $table = 'pc_inventario_movimientos';
    protected $primaryKey = 'id_movimiento';
    protected $fillable = ['id_producto', 'id_lote', 'tipo', 'cantidad', 'motivo', 'registrado_por'];

    public function producto(): BelongsTo { return $this->belongsTo(InventarioProducto::class, 'id_producto', 'id_producto'); }
    public function lote(): BelongsTo { return $this->belongsTo(InventarioLote::class, 'id_lote', 'id_lote'); }
}
