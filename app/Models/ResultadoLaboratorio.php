<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultadoLaboratorio extends Model
{
    protected $table = 'resultado_laboratorios';

    protected $fillable = [
        'detalle_orden_laboratorio_id',
        'user_id',
        'parametro',
        'resultado',
        'unidad_medida',
        'valores_referencia',
        'observaciones',
        'fecha_resultado',
    ];

    // Relación con el detalle de la orden
    public function detalleOrden(): BelongsTo
    {
        return $this->belongsTo(DetalleOrdenLaboratorio::class, 'detalle_orden_laboratorio_id');
    }

    // Relación con el usuario (bioquímico) que registró el resultado
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
