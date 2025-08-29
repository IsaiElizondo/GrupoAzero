<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'rutas';

    protected $fillable = [

        'numero_ruta',
        'cliente_id',
        'estatus_pago',
        'monto_por_cobrar',
        'fecha_hora',
        'unidad_id',
        'chofer_id',
        'estatus_entrega',
        'motivo',

    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function unidad(){
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function chofer(){
        return $this->belongsTo(User::class, 'chofer_id');
    }

    public function pedidos(){
        return $this->hasMay(RutaPedido::class, 'ruta_id');
    }

    public function orders(){
        return $this->hasManyThrough(Order::class, 'ruta_id', 'id', 'id', 'order_id');
    }
}
