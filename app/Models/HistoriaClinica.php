<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoriaClinica extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'paciente_id',
        'user_id',
        'fecha_atencion',
        'numero_historia',
        'subjetivo',
        'objetivo',
        'analisis',
        'plan',
        'signos_vitales',
        'estado',
    ];

    // Para manejar automáticamente el campo JSON
    protected $casts = [
        'signos_vitales' => 'array',
        'fecha_atencion' => 'datetime',
    ];

    // Relación con el paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    // Relación con el médico/usuario
    public function medico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
