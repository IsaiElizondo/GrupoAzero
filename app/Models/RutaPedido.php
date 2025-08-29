<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaPedido extends Model
{
    protected $table = 'ruta_pedido';

    protected $fillable = [

        'ruta_id',
        'invoice_number',
    
    ];

    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function rutaPedidos(){
        return $this->hasMany(RutaPedido::class, 'order_id');
    }

    public function rutas(){
        return $this->belongsToMany(Ruta::class, 'ruta_pedio', 'order_id', 'ruta_id');
    }
}
