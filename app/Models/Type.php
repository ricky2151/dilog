<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name'
    ];


    public function goods()
    {
        return $this->hasManyThrough('App\Models\Goods', 'App\Models\Cogs');
    }

    public function cogs(){
        return $this->hasMany('App\Models\Cogs');
    }
}
