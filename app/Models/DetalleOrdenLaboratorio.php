<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleOrdenLaboratorio extends Model
{
    // Indispensable para guardar detalles
    protected $fillable = [
        'orden_laboratorio_id', 'laboratorio_id', 'precio'
    ];

    public function laboratorio(): BelongsTo
    {
        return $this->belongsTo(Laboratorio::class);
    }

    // Relación inversa: Un detalle pertenece a una orden
    public function orden(): BelongsTo
    {
        return $this->belongsTo(OrdenLaboratorio::class, 'orden_laboratorio_id');
    }
}
