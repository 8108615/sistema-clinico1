<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenLaboratorio extends Model
{
    // Agregamos fillable para poder guardar datos
    protected $fillable = [
        'paciente_id', 'user_id', 'fecha_orden', 'total', 'estado_pago'
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleOrdenLaboratorio::class, 'orden_laboratorio_id');
    }
}
