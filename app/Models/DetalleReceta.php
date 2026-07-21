<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleReceta extends Model
{
    protected $table = 'detalle_recetas';

    protected $fillable = [
        'receta_medica_id',
        'medicamento',
        'dosis',
        'duracion',
        'indicaciones',
    ];

    public function receta(): BelongsTo
    {
        return $this->belongsTo(RecetaMedica::class, 'receta_medica_id');
    }
}
