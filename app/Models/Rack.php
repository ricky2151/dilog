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

    public function updateManyAtribut($goods_rack_update){
        if(!is_null($goods_rack_update)){
            foreach ($goods_rack_update as $update) {
                $data = Arr::except($update, ['id']);
                $this->goodsRack()->find($update["id"])->update($data);
            }
        }
    }

    public function deleteManyAtribut($goods_rack_delete){
        if(!is_null($goods_rack_delete)){
            foreach ($goods_rack_delete as $delete) {
                $this->goodsRack()->find($delete["id"])->delete();
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
