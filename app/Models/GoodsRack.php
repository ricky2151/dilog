<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class GoodsRack extends Model
{
    protected $fillable = [
        'goods_id', 'rack_id','stock'
    ];

    public function getMasterData(){
        return ['goods' => Goods::all(['id','name']),'racks' => Rack::all(['id','name'])];
    }

    public function getDataAndRelation($id){
        $data = $this->with('goods:id,name','rack:id,name')->where('id',$id)->first();
        $data = Arr::except($data, 
            ['goods_id',
            'rack_id',
            'created_at',
            'updated_at',
        ]);

        return $data;
    }

    public function index(){
        return $this->latest()->get()->map(function ($goodsRack) {
            $goodsRack = Arr::add($goodsRack, 'goods_name', $goodsRack['goods']['name']);
            $goodsRack = Arr::add($goodsRack, 'rack_name', $goodsRack['rack']['name']);

            return Arr::except($goodsRack, ['goods','rack']);
        });
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
