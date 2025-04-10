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
        $list = DB::select(DB::raw("SELECT COUNT(*) AS tot FROM requisitions"));

        return $list[0]->tot;
    }



}