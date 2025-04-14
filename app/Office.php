<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Office extends Model
{

    protected $table = "offices";

    protected $fillable = [
        'name', 'created_at', 'updated_at'
    ];



    public function catalog() :array {
        $res = $this->all();
        $arr = [];
        foreach($res as $r){
            $arr[$r->name] = $r->name;
        }
    return $arr;
    }

}
