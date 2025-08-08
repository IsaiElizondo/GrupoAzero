<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DevolucionParcial extends Model
{
    
    use HasFactory;

    protected $table = 'devoluciones_parciales';

    protected $fillable = [

        'order_id',
        'folio',
        'motivo',
        'descripcion',
        'tipo',
        'file',
        'created_by'

    ];

    public function pedido(){

        return $this->belongsTo(\App\Order::class, 'order_id');

    }

    public function usuario(){

        return $this->belongsTo(\App\User::class, 'created_by');

    }

}
