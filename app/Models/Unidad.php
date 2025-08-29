<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'unidades';

    protected $fillable = [

        'nombre_unidad',
        'chofer_id',

    ];

    public function chofer(){
        return $this->belongsTo(User::class, 'chofer_id');
    }

    public function rutas(){
        return $this->hasMany(Ruta::class, 'unidad_id');
    }
}
