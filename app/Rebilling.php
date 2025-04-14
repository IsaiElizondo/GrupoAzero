<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rebilling extends Model
{
    protected $fillable = [
        'order_id', 'reason_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }

    public function evidences()
    {
        return $this->hasMany(Evidence::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}
