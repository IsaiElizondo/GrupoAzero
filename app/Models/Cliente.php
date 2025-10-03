<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table ='clientes';

    protected $fillable = [

        'nombre',
        'codigo_cliente',
        'celular',

    ];

    public function direcciones(){
        return $this->hasMany(DireccionCliente::class, 'cliente_id');
    }

    public function rutas(){
        return $this->hasMany(Ruta::class, 'cliente_id');
    }
}
