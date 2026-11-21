<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consulta extends Model
{
    protected $table = 'pc_consultas';
    protected $primaryKey = 'id_consulta';
    protected $fillable = [
        'id_paciente', 'id_expediente', 'id_tratamiento', 'fecha_consulta',
        'hora_consulta', 'motivo_consulta', 'exploracion', 'diagnostico',
        'procedimiento', 'indicaciones', 'observaciones', 'proxima_revision',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return ['fecha_consulta' => 'date', 'proxima_revision' => 'date'];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'id_expediente', 'id_expediente');
    }

    public function tratamiento(): BelongsTo
    {
        return $this->belongsTo(Tratamiento::class, 'id_tratamiento', 'id_tratamiento');
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por', 'id');
    }
}
