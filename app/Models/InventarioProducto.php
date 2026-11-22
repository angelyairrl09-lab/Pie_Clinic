<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioProducto extends Model
{
    protected $table = 'pc_inventario_productos';
    protected $primaryKey = 'id_producto';
    protected $fillable = ['codigo', 'nombre', 'categoria', 'unidad', 'stock_minimo', 'precio_compra', 'precio_venta', 'descripcion', 'activo'];
    protected $casts = ['activo' => 'boolean', 'stock_minimo' => 'integer', 'precio_compra' => 'decimal:2', 'precio_venta' => 'decimal:2'];

    public function lotes(): HasMany { return $this->hasMany(InventarioLote::class, 'id_producto', 'id_producto'); }
    public function movimientos(): HasMany { return $this->hasMany(InventarioMovimiento::class, 'id_producto', 'id_producto'); }
}
