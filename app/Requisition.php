<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Requisition extends Model
{
    protected $fillable = [
        'number', 'document','status_id'
    ];
    
    public static function countAll(){
        $list = DB::select("SELECT COUNT(*) AS tot FROM requisitions");
        $list = json_decode(json_encode($list), true);
        return $list[0]['tot'];
        
    }



}