<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionCliente extends Model
{
    protected $table = 'clientes_direcciones';

    protected $fillable = [

        'cliente_id',
        'nombre_direccion',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'celular',
        'url_mapa',
        'instrucciones',

    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
