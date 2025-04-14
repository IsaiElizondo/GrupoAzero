<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partial extends Model
{
    protected $fillable = [
        'invoice', 'order_id', 'status_id','status_4','status_5','status_6','status_7','end_at'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}
