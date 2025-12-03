<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ruta;
use App\Models\UnidadChofer;

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

    public function choferes(){
        return $this->belongsToMany(User::class, 'unidad_chofer', 'unidad_id', 'chofer_id')
                    ->withPivot(['uso_count', 'last_used_at']);
    }

}
