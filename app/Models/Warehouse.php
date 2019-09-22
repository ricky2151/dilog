<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Warehouse extends Model
{
    use SoftDeletes;

    public function updateManyAtribut($racks_update){
        if(!is_null($racks_update)){
            foreach ($racks_update as $update) {
                $data = Arr::except($update, ['id']);
                $this->racks()->find($update["id"])->update($data);
            }
        }
    }

    public function deleteManyAtribut($racks_delete){
        if(!is_null($racks_delete)){
            foreach ($racks_delete as $delete) {
                $this->racks()->find($delete["id"])->delete();
            }
        }
    }

    protected $fillable = [
        'name', 'address','lat','lng','telp','email','pic'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

    public function racks(){
        return $this->hasMany('App\Models\Rack');
    }

    public function priceSelling(){
        return $this->hasMany('App\Models\PriceSelling')->orderBy('updated_at', 'desc');
    }

    public function stockOpnames(){
        return $this->hasMany("App\Models\stockOpname");
    }

    public function updateRack($racks){
        foreach($racks as $rack){
            if($rack['type'] == 1) {
                $this->racks()->create($rack);
            }
            else if($rack['type'] == 0) {
                $this->racks()->find($rack['id'])->update($rack);
            } else {
                $this->racks()->find($rack['id'])->goodsRacks()->delete();
                $this->racks()->find($rack['id'])->delete();

            }
        }
    }

    public function getRackWithHaveGoods(){
        $racks = $this->racks->map(function ($item) {
            $item = Arr::add($item, 'is_have_goods',collect($item->goodsRacks)->isNotEmpty());
            return  Arr::except($item,['goodsRacks']);
        });

        return $racks;
    }

    public function getGoods(){
        $goods = $this->racks->map(function ($item) {
            $goodsRack = $item->goodsRacks->map(function ($data) {
                return [$data['goods']];
            });
            return $goodsRack;
        })->flatten()->unique(function ($item) {
            return $item['id'];
        })->flatten();

        return $goods;
    }

    public function getGoodsWithStock(){
        $goodsWithStock = $this->getGoods()->map(function($data){
            return ['goods_id' => $data['id'], 'goods_name' => $data['name'], 'current_stock' => Goods::find($data['id'])->stock()];
        });

        return $goodsWithStock;
    }

    public function getGoodsRack(){
        $goodsRacks = $this->racks->map(function ($item) {
            $goodsRack = $item->goodsRacks->map(function ($data) {
                return ['rack_id'=>$data['rack']['id'],'rack_name'=>$data['rack']['name'], 'stock'=>$data['stock'],'goods_id'=>$data['goods']['id'], 'goods_name'=>$data['goods']['name']];
            });
            return $goodsRack;
        })->flatten(1);

        return $goodsRacks;
    }
}
