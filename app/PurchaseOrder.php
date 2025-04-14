<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'required',
        'number',
        'document',
        'requisition',
        'iscovered',
        'order_id',
        'code_smaterial',
        'document_5',
        'document_6',
        'document_7',
        'created_by',
        'end_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function office(){
        $usr = \App\User::where("id",$this->created_by)->first();
        return !empty($usr) ? $usr->office : "";
    }

}
