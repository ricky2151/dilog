<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Rack extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'warehouse_id'
    ];

    public function updateGoodsRack($goodsRacks){
        foreach($goodsRacks as $goodsRack){
            if($goodsRack['type'] == 1) {
                $this->goodsRacks()->create($goodsRack);
            }
            else if($goodsRack['type'] == 0) {
                $this->goodsRacks()->find($goodsRack['id'])->update($goodsRack);
            } else {
                $this->goodsRacks()->find($goodsRack['id'])->delete();
            }
        }
    }

    public function selectedColoumns($data, $coloumn, $selectedColoumns){
        $selectedColoumns = collect($selectedColoumns);
        return $data->map(function($item) use($selectedColoumns, $coloumn){
            $data = collect();
            foreach ($selectedColoumns as $selectedColoumn) {
                $data[Str::snake($selectedColoumn)] = $item["$selectedColoumn"];
            }
            if(is_null($coloumn)){
                return $data;
            }
            else{
                return ["$coloumn"=>$data];
            }
        });
    }

    public function getDataAndRelation($id){
        $data = $this->with(['goodsRacks','goodsRacks.goods:id,name','warehouse:id,name,address,lat,lng,telp,email,pic'])->where('id',$id)->first();
        $tampGoodsRack = $this->selectedColoumns($data['goodsRacks'],null,["id","stock","goods"]);

        $data = Arr::except($data, 
            ['warehouse_id',
            'warehouse_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'goodsRacks'
            ]);
        $data['goods_racks'] = $tampGoodsRack;
        return $data;
    }

    public function getMasterData(){
        return ['warehouses' => Warehouse::all(['id','name']),'goods' => Goods::all(['id','name'])];
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function goodsRacks(){
        return $this->hasMany('App\Models\GoodsRack');
    }
}
