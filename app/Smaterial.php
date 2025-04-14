<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Smaterial extends Model
{
    protected $fillable = [
        'order_id', 'code',"status_id","status_4","status_5","status_6","status_7","end_at"
    ];

    protected $table = "smaterial";

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
