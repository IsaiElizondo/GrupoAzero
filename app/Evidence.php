<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Evidence extends Model
{
    protected $fillable = [
        'file', 'cancelation_id', 'rebilling_id', 'debolution_id'
    ];

    public function cancelation()
    {
        return $this->belongsTo(Cancelation::class);
    }

    public function rebilling()
    {
        return $this->belongsTo(Rebilling::class);
    }

    public function debolution()
    {
        return $this->belongsTo(Debolution::class);
    }

    public static function FromOrder(int $order_id){
        $q="SELECT e.* 
        FROM evidence e 
        LEFT JOIN cancelations c ON c.id = e.cancelation_id 
        LEFT JOIN debolutions d ON d.id = e.debolution_id 
        LEFT JOIN rebillings r ON r.id = e.rebilling_id 
        JOIN orders o ON o.id IN(c.order_id, d.order_id, r.order_id)
        WHERE o.id = '$order_id'";
       // echo $q;
        return  DB::select($q);
     }

}
