<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tratamiento extends Model
{
    protected $table = 'pc_tratamientos';

    protected $primaryKey = 'id_tratamiento';

    protected $fillable = [
        'id_paciente',
        'nombre',
        'descripcion',
        'indicaciones',
        'fecha_inicio',
        'fecha_fin',
        'costo',
        'estado',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'costo' => 'decimal:2',
    ];

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
            'registrado_por',
            'id'
        );
    }
}
