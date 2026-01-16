<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ruta;
use App\Order;
use App\Smaterial;
use App\Partial;

class RutaPedido extends Model
{
    protected $table = 'ruta_pedido';

    protected $fillable = [
        'ruta_id',
        'order_id',
        'partial_folio',
        'smaterial_folio',
        'estatus_pago',
        'estatus_entrega',
        'monto_por_cobrar',
        'numero_pedido_ruta',
        'cliente_codigo',
        'cliente_nombre',
        'partial_folio',
        'smaterial_folio',
        'motivo',
        'tipo_subproceso',
        'subproceso_id',
        'partial_id',
        'smaterial_id'
    ];

    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    public function partial(){
        return $this->belongsTo(Partial::class, 'partial_id');
    }

    public function smaterial(){
        return $this->belongsTo(Smaterial::class, 'smaterial_id');
    }
}
