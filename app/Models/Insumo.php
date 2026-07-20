<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable = [
        'categoria_id', 'nombre', 'descripcion', 'stock', 'stock_minimo', 'precio_compra'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
