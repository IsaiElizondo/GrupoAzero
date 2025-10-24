<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\RutaPedido;
use App\User;
use App\Order;

class Ruta extends Model
{
    protected $table = 'rutas';

    protected $fillable = [
        'numero_ruta',
        'cliente_id',
        'fecha_hora',
        'unidad_id',
        'chofer_id',
        'estatus_entrega',
        'motivo',
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id')->withTrashed();
    }

    public function unidad(){
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function chofer(){
        return $this->belongsTo(User::class, 'chofer_id');
    }

    public function pedidos(){
        return $this->hasMany(RutaPedido::class, 'ruta_id');
    }

    public function orders(){
        return $this->belongsToMany(Order::class, 'ruta_pedido', 'ruta_id', 'order_id')
                    ->withPivot('estatus_pago', 'monto_por_cobrar')
                    ->withTimestamps();
    }
}
