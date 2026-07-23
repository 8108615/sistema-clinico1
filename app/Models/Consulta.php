<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consulta extends Model
{
    protected $fillable = [
        'paciente_id',
        'consultorio_id',
        'usuario_id',
        'caja_id',
        'fecha_atencion',
        'precio',
        'estado'
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function consultorio(): BelongsTo
    {
        return $this->belongsTo(Consultorio::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }

    // Relación inversa: Una consulta genera una historia clínica
    public function historiaClinica(): HasOne
    {
        return $this->hasOne(HistoriaClinica::class, 'consulta_id');
    }
}
