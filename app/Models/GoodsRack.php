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
        return ['goods' => Goods::all(['id','name']),'racks' => Rack::all(['id','name']),'category_price_selling'=>CategoryPriceSelling::all(['id','name']),'sources'=>Source::all(['id','name'])];
    }

    public function updateManyAtribut($price_sellings_update){
        if(!is_null($price_sellings_update)){
            foreach ($price_sellings_update as $update) {
                $data = Arr::except($update, ['id']);
                $this->priceSellings()->find($update["id"])->update($data);
            }
        }
    }

    public function deleteManyAtribut($price_sellings_delete){
        if(!is_null($price_sellings_delete)){
            foreach ($price_sellings_delete as $delete) {
                $this->priceSellings()->find($delete["id"])->delete();
            }
        }
    }
    
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
        return $this->belongsToMany('App\Models\Source','batchs','goods_rack_id','source_id')->withPivot('stock', 'batch_number')->withTimestamps();
    }
}
