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

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function ordenLaboratorios()
    {
        return $this->hasMany(OrdenLaboratorio::class);
    }

    public function puedeEditar()
    {
        // Verificamos si tiene registros asociados
        $tieneConsultas = $this->consultas()->exists();
        $tieneLaboratorios = $this->ordenLaboratorios()->exists();

        // Solo puede editar si está abierta Y no tiene registros
        return $this->estado === 'ABIERTA' && !$tieneConsultas && !$tieneLaboratorios;
    }
}
