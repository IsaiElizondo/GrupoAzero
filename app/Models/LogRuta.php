<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class LogRuta extends Model
{
    use HasFactory;

    protected $table = 'logs_rutas';

    protected $fillable = [

        'status',
        'action',
        'ruta_id',
        'order_id',
        'user_id',
        'department_id',

    ];

    public function ruta(){
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }
}
