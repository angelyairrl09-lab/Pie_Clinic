<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoFinanciero extends Model
{
    protected $table = 'pc_finanzas_movimientos';
    protected $primaryKey = 'id_movimiento';
    protected $fillable = ['tipo', 'fecha', 'concepto', 'categoria', 'monto', 'metodo_pago', 'referencia', 'observaciones', 'id_tratamiento', 'registrado_por'];
    protected $casts = ['fecha' => 'date', 'monto' => 'decimal:2'];

    public function tratamiento(): BelongsTo { return $this->belongsTo(Tratamiento::class, 'id_tratamiento', 'id_tratamiento'); }
    public function usuarioRegistro(): BelongsTo { return $this->belongsTo(User::class, 'registrado_por'); }
}
