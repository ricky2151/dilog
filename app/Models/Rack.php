<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'warehouse_id'
    ];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function goodsRack(){
        return $this->hasMany('App\Models\GoodRack');
    }
}
