<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolucionParcialArchivos extends Model
{
    
    use HasFactory;

    protected $table = 'devolucion_parcial_archivos';

    protected $fillable = [
        'devolucion_parcial_id',
        'file',
    ];

    public function devolucionp(){

        return $this->belongsTo(\App\DevolucionParcial::class, 'devolucion_parcial_id');

    }

}
