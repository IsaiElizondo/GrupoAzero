<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
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
        return $this->hasMany(Rutas::class, 'cliente_id');
    }
}
