<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Picture extends Model
{
    protected $fillable = [
        'picture', 'user_id', 'order_id', 'partial_id', 'created_at','shipment_id','event','smaterial_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function partial()
    {
        return $this->belongsTo(Partial::class);
    }



    public static function EnPuerta(int $order_id, string $event="") : array{
        $q="SELECT p.*, 
        pa.invoice AS `partial`, 
        o.invoice AS invoice, 
        o.invoice_number AS invoice_number 
        FROM pictures p 
        LEFT JOIN shipments s ON s.id = p.shipment_id 
        JOIN orders o ON o.id IN( p.order_id, pa.order_id, s.order_id)  
        WHERE o.id = '$order_id'";
        $q.= !empty($event) ? " AND p.event = '$event'" : "";

        return DB::select($q);
    }

}
