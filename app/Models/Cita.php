<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    protected $table = 'pc_citas';
    protected $primaryKey = 'id_cita';

    protected $fillable = [
        'id_paciente',
        'fecha_cita',
        'hora_inicio',
        'duracion_minutos',
        'motivo',
        'observaciones',
        'estado',
        'registrado_por'
    ];

    protected function casts(): array
    {
        return [
            'fecha_cita' => 'date',
            'duracion_minutos' => 'integer'
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(
            Paciente::class,
            'id_paciente',
            'id_paciente'
        );
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'registrado_por'
        );
    }
}
