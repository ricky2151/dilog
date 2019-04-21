<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cogs extends Model
{
    //
    protected $fillable = [
        'nominal','name','type_id'
    ];

    public function type(){
        return $this->belongsTo('App\Models\Type');
    }

    public function goods(){
        return $this->hasMany('App\Models\Goods');
    }

    public function cogsComponents(){
        return $this->hasMany('App\Models\CogsComponent');
    }
}
