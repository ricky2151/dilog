<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Supplier extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name_company','name_owner','name_pic','name_sales','address','no_telp_company','no_telp_owner','email','fax','npwp','no_rek'
    ];

    public function getMasterData(){
        return ['goods' => Goods::all(['id','name'])];
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
        $data = $this->with(['pricelists','pricelists.goods:id,name'])->where('id',$id)->first();
        $tampPricelist = $this->selectedColoumns($data['pricelists'],null,["id","price","goods"]);

        $data = Arr::except($data,[
            'created_at',
            'updated_at',
            'deleted_at',
            'pricelists',
        ]);
        $data['pricelists'] = $tampPricelist;
        return $data;
    }

    public function pricelists(){
        return $this->hasMany('App\Models\Pricelist');
    }

    public function purchaseOrders(){
        return $this->hasMany('App\Models\PurchaseOrder');
    }

    public function goodsWithPricelists(){
        $data = $this->pricelists->groupBy('goods_id')->map(function ($item, $key) {
            $data = collect(Goods::find($key,['id','uuid','name']));
            $data = Arr::add($data,'pricelists',$item);
            return $data;

        })->sortKeys();

        return $data->values();
    }

}
