<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_cierre',
        'total_efectivo',
        'total_transferencia',
        'estado',
        'observaciones',
    ];

    // Relación: Una caja pertenece a un usuario (cajero)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
