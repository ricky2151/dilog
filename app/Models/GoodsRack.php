<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class GoodsRack extends Model
{
    protected $fillable = [
        'goods_id', 'rack_id','stock'
    ];

    public static function allDataCreate(){
        return ['goods' => Goods::all(['id','name']),'racks' => Rack::all(['id','name'])];
    }
    
    protected $table = 'goods_rack';

    public function rack(){
        return $this->belongsTo('App\Models\Rack','rack_id','id');
    }

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    // public function sources(){
    //     return $this->belongsToMany('App\Models\Source','batchs','goods_rack_id','source_id')->withPivot('stock', 'batch_number')->withTimestamps();
    // }
}
