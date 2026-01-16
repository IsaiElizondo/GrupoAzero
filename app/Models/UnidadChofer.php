<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadChofer extends Model
{
    protected $table = 'unidad_chofer';

    protected $fillable = [
        'unidad_id',
        'chofer_id',
        'uso_count',
        'last_used_at',
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function chofer()
    {
        return $this->belongsTo(User::class, 'chofer_id');
    }
}
