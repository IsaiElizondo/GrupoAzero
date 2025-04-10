<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Debolution extends Model
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

    public function getReason(){
        return $this->hasOne(Reason::class, 'id', 'reason_id');
    }


    public static function FromOrder(int $order_id) : array {
        $q="SELECT d.*, r.reason AS reason 
        FROM debolutions d 
        JOIN reasons r ON r.id = d.reason_id 
        WHERE d.order_id = '$order_id'";
        //$this->table("debolutions")->join("reasons")
        return DB::select($q);
    }


}
