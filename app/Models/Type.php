<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    protected $fillable = [
        'name'
    ];


    public function goods()
    {
        return $this->hasManyThrough('App\Models\Goods', 'App\Models\Goods');
    }

    public function cogs(){
        return $this->hasMany('App\Models\Cogs');
    }
}
