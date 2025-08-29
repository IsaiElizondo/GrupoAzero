<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionCliente extends Model
{
    protected $table = 'clientes_direcciones';

    protected $fillable = [

        'cliente_id',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'url_mapa',
        'instrucciones',

    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
