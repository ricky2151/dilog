<?php

namespace App\Models;
use Illuminate\Support\Arr;

use Illuminate\Database\Eloquent\Model;

//status : 0 for new, 1 for complete

class PurchaseRequest extends Model
{
    protected $fillable = [
        'code', 'status','created_by_user_id'
    ];

    public function purchaseRequestDetails(){
        return $this->hasMany('App\Models\PurchaseRequestDetail');
    }

    public function purchaseOrders(){
        return $this->hasMany('App\Models\PurchaseOrder');
    }

    public function rekaps(){
        return $this->hasMany('App\Models\Rekap');
    }

    public function purchaseRequestDetailsNotYetBePo(){
        return $this->purchaseRequestDetails->where('is_created_as_po',0);
    }

    public function purchaseRequestDetailsBePo(){
        return $this->purchaseRequestDetails->where('is_created_as_po',1);
    }

    public function setComplete(){
        if($this->rekaps->sum('total_required_by_mr')!=0){
            if($this->purchaseRequestDetailsBePo()->sum('qty')/$this->rekaps->sum('total_required_by_mr') >= 1){
                $this->status = 1;
                $this->save();
            }
        }
    }

    public function prGetStatusName(){
        switch($this->status){
            case 0: return "new";
            case 1: return "complete";
        }
    }


    public function getDataPurchaseRequestDetails(){
        return $this->purchaseRequestDetails->map(function($item){
            return [
                'id'=> $item['id'],
                'supplier_id' => $item['supplier_id'],
                'supplier_name' => $item['supplier']['name_company'],
                'purchase_request_id'=> $item['purchase_request_id'],
                'goods_id'=> $item['goods_id'],
                'goods_name'=> $item['goods']['name'],
                'qty'=> $item['qty'],
                'pricelist_id'=> $item['pricelist_id'],
                'price'=> $item['price'],
                'subtotal'=> $item['price']*$item['qty'],
            ];
        });
    }

    public function getRekapsToPo(){
        // return  $this->purchaseRequestDetails->where('is_created_as_po',0);
        return $this->purchaseRequestDetailsNotYetBePo()->groupBy('supplier_id')->map(function($item, $key){
            $supplier = Supplier::find($key);
            return [
                'supplier_id' => $supplier['id'],
                'supplier_name' => $supplier['name_company'],
                'total' => $item->sum(function($item){return $item['price']*$item['qty'];}),
                'purchase_request_details' => $item->map(function($item){
                    return[
                        'id'=> $item['id'],
                        'purchase_request_id'=> $item['purchase_request_id'],
                        'goods_id'=> $item['goods_id'],
                        'goods_name'=> $item['goods']['name'],
                        'qty'=> $item['qty'],
                        'price'=> $item['price'],
                        'subtotal'=> $item['price']*$item['qty'],
                    ];
                })
            ];
        })->values();
    }

    public function getRekapDatas(){
        return $this->rekaps->map(function($item){
            $goods = Goods::find($item['goods_id']);
            return [
                "id" => $item['id'],
                "goods_id" => $item['goods_id'], 
                'goods_name'=>$goods['name'],
                'total_required_by_mr' => $item['total_required_by_mr'],
                'stock'=> $goods->stock(),
                'total_already_po' => $this->purchaseRequestDetailsBePo()->where('goods_id',$item['goods_id'])->sum('qty'),
                'suppliers' => $goods->suppliers->unique(function ($item) {
                    return $item['id'];
                })->flatten(1)->map(function($item){
                    return Arr::except($item,['pivot']);
                }),
                'pricelists' => $goods->pricelists    
            ];
        });
    }
}
