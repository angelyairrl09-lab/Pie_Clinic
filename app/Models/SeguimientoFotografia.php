<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoFotografia extends Model
{
    protected $table = 'pc_seguimiento_fotografias';
    protected $primaryKey = 'id_fotografia';
    protected $fillable = ['id_seguimiento', 'ruta', 'nombre_original'];
    public function seguimiento() { return $this->belongsTo(Seguimiento::class, 'id_seguimiento', 'id_seguimiento'); }
}
