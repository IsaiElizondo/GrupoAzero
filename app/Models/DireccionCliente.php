<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DireccionCliente extends Model
{
    protected $table = 'clientes_direcciones';

    protected $fillable = [

        'cliente_id',
        'nombre_direccion',
        'tipo_residencia',
        'direccion',
        'ciudad',
        'estado',
        'colonia',
        'codigo_postal',
        'celular',
        'telefono',
        'nombre_recibe',
        'url_mapa',
        'instrucciones',

    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'cliente_direccion_id');
    }
}
