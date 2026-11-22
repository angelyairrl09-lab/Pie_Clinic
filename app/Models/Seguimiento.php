<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'pc_seguimientos';
    protected $primaryKey = 'id_seguimiento';
    protected $fillable = ['id_tratamiento', 'fecha_consulta', 'observaciones', 'evolucion', 'avances', 'proxima_cita', 'registrado_por'];
    protected $casts = ['fecha_consulta' => 'date', 'proxima_cita' => 'datetime'];

    public function tratamiento() { return $this->belongsTo(Tratamiento::class, 'id_tratamiento', 'id_tratamiento'); }
    public function usuarioRegistro() { return $this->belongsTo(User::class, 'registrado_por', 'id'); }
    public function fotografias() { return $this->hasMany(SeguimientoFotografia::class, 'id_seguimiento', 'id_seguimiento'); }
}
