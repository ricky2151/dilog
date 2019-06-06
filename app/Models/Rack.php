<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Rack extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'warehouse_id'
    ];

    public function updateGoodsRack($goodsRacks){
        foreach($goodsRacks as $goodsRack){
            if($goodsRack['type'] == 1) {
                $this->goodsRack()->create($goodsRack);
            }
            else if($goodsRack['type'] == 0) {
                $this->goodsRack()->find($goodsRack['id'])->update($goodsRack);
            } else {
                $this->goodsRack()->find($goodsRack['id'])->delete();
            }
        }
    }



    public static function allDataCreate(){
        return ['warehouses' => Warehouse::all(['id','name']),'goods' => Goods::all(['id','name'])];
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function goodsRack(){
        return $this->hasMany('App\Models\GoodsRack');
    }
}
