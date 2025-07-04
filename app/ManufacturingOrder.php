<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturingOrder extends Model
{
    protected $fillable = [
        'required',
        'number',
        'document',
        'documentc',
        'iscovered',
        'order_id',
        'status_id',
        'status_1',
        'status_3',
        'status_4',
        'status_7',
        'created_by',
        'manufactured_by',
        'end_at'
    ];

     public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con el usuario que creó la orden
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relación con el usuario que manufacturó
    public function manufacturador()
    {
        return $this->belongsTo(User::class, 'manufactured_by');
    }

    // Método para obtener la sucursal del creador
    public function officeCreated(): string
    {
        return $this->creador && $this->creador->office ? $this->creador->office : '';
    }

    // Método para obtener la sucursal del manufacturador
    public function office(): string
    {
        return $this->manufacturador && $this->manufacturador->office ? $this->manufacturador->office : '';
    }


}
