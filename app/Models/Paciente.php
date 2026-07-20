<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Paciente extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'pacientes';

    protected $fillable = [
        'nombres', 'apellidos', 'ci', 'fecha_nacimiento', 'genero',
        'celular', 'correo', 'direccion', 'grupo_sanguineo',
        'peso', 'talla', 'alergias', 'contacto_emergencia',
        'parentesco_emergencia', 'observaciones', 'estado'
    ];

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function historiasClinicas()
    {
        return $this->hasMany(HistoriaClinica::class);
    }
}
