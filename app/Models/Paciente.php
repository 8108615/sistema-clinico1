<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pacientes';

    protected $fillable = [
        'nombres', 'apellidos', 'ci', 'fecha_nacimiento', 'genero', 
        'celular', 'correo', 'direccion', 'grupo_sanguineo', 
        'peso', 'talla', 'alergias', 'contacto_emergencia', 
        'parentesco_emergencia', 'observaciones', 'estado'
    ];
}
