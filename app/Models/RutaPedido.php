<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ruta;
use App\Order;

class RutaPedido extends Model
{
    protected $table = 'ruta_pedido';

    protected $fillable = [
        'ruta_id',
        'order_id',
        'estatus_pago',
        'monto_por_cobrar',
    ];

    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
