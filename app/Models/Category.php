<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public function goods(){
        return $this->belongsToMany('App\Models\Good','category_goods','category_id','goods_id')->withTimestamps();
    }
}
