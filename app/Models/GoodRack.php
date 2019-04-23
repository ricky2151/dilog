<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodRack extends Model
{
    //
    protected $fillable = [
        'goods_id', 'rack_id','stock'
    ];
    protected $table = 'goods_rack';

    public function rack(){
        return $this->belongsTo('App\Models\Rack','rack_id','id');
    }

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    public function priceSellings(){
        return $this->hasMany('App\Models\PriceSelling');
    }

    public function sources(){
        return $this->belongsToMany('App\Models\Source','batch','good_rack_id','source_id')->withPivot('stock', 'batch_number')->withTimestamps();
    }
}
