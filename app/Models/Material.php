<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //
    protected $fillable = [
        'goods_id', 'total','adjust'
    ];

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }
}
