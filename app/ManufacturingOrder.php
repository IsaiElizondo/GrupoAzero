<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturingOrder extends Model
{
    protected $fillable = [
        'required',
        'number',
        'document',
        'documentc',
        'iscovered',
        'order_id',
        'status_id',
        'status_1',
        'status_3',
        'status_4',
        'status_7',
        'created_by',
        'manufactured_by',
        'end_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function office() : string {
        $usr = \App\User::where("id",$this->manufactured_by)->first();
        return (!empty($usr) && !empty($user->office)) ? $usr->office : "";
    }

    public function officeCreated() : string {
        $usr = \App\User::where("id",$this->created_by)->first();
        return (!empty($usr) && !empty($user->office))  ? $usr->office : "";
    }


}
