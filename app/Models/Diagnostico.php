<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diagnostico extends Model
{
    protected $table = 'pc_diagnosticos';
    protected $primaryKey = 'id_diagnostico';

    protected $fillable = [
        'id_paciente',
        'fecha_diagnostico',
        'titulo',
        'descripcion',
        'observaciones',
        'recomendaciones',
        'registrado_por'
    ];

    protected function casts(): array
    {
        return [
            'fecha_diagnostico' => 'date'
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

    public function imagenes(): HasMany
    {
        return $this->hasMany(
            DiagnosticoImagen::class,
            'id_diagnostico',
            'id_diagnostico'
        );
    }
}
