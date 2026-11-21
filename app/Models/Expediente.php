<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expediente extends Model
{
    protected $table = 'pc_expedientes';

    protected $primaryKey = 'id_expediente';

    protected $fillable = [
        'id_paciente',
        'fecha_apertura',
        'tipo_sangre',
        'alergias',
        'enfermedades_cronicas',
        'medicamentos_actuales',
        'antecedentes_personales',
        'antecedentes_familiares',
        'antecedentes_quirurgicos',
        'antecedentes_podologicos',
        'motivo_consulta_inicial',
        'observaciones_generales',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_apertura' => 'date',
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
            'registrado_por',
            'id'
        );
    }
}
