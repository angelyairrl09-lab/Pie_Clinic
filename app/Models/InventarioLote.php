<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioLote extends Model
{
    protected $table = 'pc_inventario_lotes';
    protected $primaryKey = 'id_lote';
    protected $fillable = ['id_producto', 'numero_lote', 'fecha_caducidad', 'existencia'];
    protected $casts = ['fecha_caducidad' => 'date', 'existencia' => 'integer'];

    public function producto(): BelongsTo { return $this->belongsTo(InventarioProducto::class, 'id_producto', 'id_producto'); }
    public function movimientos(): HasMany { return $this->hasMany(InventarioMovimiento::class, 'id_lote', 'id_lote'); }
}
