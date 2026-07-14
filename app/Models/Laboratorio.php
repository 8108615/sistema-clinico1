<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'categoria',
        'descripcion',
        'precio',
        'dias_entrega',
        'requiere_ayuno',
        'estado'
    ];
}
