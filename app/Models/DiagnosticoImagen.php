<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticoImagen extends Model
{
    protected $table = 'pc_diagnostico_imagenes';
    protected $primaryKey = 'id_imagen';

    protected $fillable = [
        'id_diagnostico',
        'ruta_imagen',
        'nombre_original',
        'tipo_mime'
    ];

    public function diagnostico(): BelongsTo
    {
        return $this->belongsTo(
            Diagnostico::class,
            'id_diagnostico',
            'id_diagnostico'
        );
    }
}
