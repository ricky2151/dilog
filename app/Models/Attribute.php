<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = [
        'name'
    ];

    public function goods(){
        return $this->belongsToMany('App\Models\Good','attribute_goods','attribute_id','goods_id')->withPivot('value')->withTimestamps();
    }
}
