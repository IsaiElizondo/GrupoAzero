<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'unidades';

    protected $fillable = [

        'nombre_unidad',
        'marca',
        'modelo',
        'numero_de_serie',
        'placas',
        'epp',
        'estatus',
        
    ];


    public function rutas(){
        return $this->hasMany(Ruta::class, 'unidad_id');
    }
}
