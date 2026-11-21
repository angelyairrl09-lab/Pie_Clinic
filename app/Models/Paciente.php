<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pc_pacientes';

    protected $primaryKey = 'id_paciente';

    protected $fillable = [
        'numero_paciente',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'correo',
        'calle',
        'numero_exterior',
        'numero_interior',
        'colonia',
        'municipio',
        'estado',
        'codigo_postal',
        'ocupacion',
        'contacto_emergencia',
        'telefono_emergencia',
        'parentesco_emergencia',
        'observaciones',
        'activo',
        'registrado_por'
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'activo' => 'boolean'
        ];
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(
            $this->nombre . ' ' .
            $this->apellido_paterno . ' ' .
            $this->apellido_materno
        );
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }

    public function getDomicilioAttribute(): string
    {
        return implode(', ', array_filter([
            trim(
                $this->calle . ' ' .
                $this->numero_exterior
            ),
            $this->numero_interior
                ? 'Int. ' . $this->numero_interior
                : null,
            $this->colonia,
            $this->municipio,
            $this->estado,
            $this->codigo_postal
                ? 'C.P. ' . $this->codigo_postal
                : null
        ]));
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'registrado_por'
        );
    }

    public function diagnosticos()
{
    return $this->hasMany(
        Diagnostico::class,
        'id_paciente',
        'id_paciente'
    );
}

public function citas()
{
    return $this->hasMany(
        Cita::class,
        'id_paciente',
        'id_paciente'
    );
}


public function tratamientos()
{
    return $this->hasMany(
        \App\Models\Tratamiento::class,
        'id_paciente',
        'id_paciente'
    );
}

public function expediente(): \Illuminate\Database\Eloquent\Relations\HasOne
{
    return $this->hasOne(
        \App\Models\Expediente::class,
        'id_paciente',
        'id_paciente'
    );
}

}
