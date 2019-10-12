<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestDetail extends Model
{
    //Status is condition this Material Request where value 1 = responded, 0 = not yet responded
    protected $fillable = [
        'purchase_request_id', 'goods_id','qty', 'notes', 'pricelist_id', 'price', 'supplier_id', 'is_created_as_po'
    ];

    public function setCreatedPo(){
        $this->is_created_as_po = 1;
        $this->save();
    }

    public function getMasterData(){
        $goods = collect($this->load(['purchaseRequest.rekaps'])['purchaseRequest']['rekaps'])->map(function($data){
            return $data['goods_id'];
        });
        return ['goods' => Goods::whereIn('id',$goods)->get()->map(function($data){
                // $data = collect($data);
            $suppliers = $data['suppliers']->map(function($supplier) use($data){
                $pricelists = $supplier['pricelists']->where('goods_id', $data['id'])->values();
                $supplier = collect($supplier);
                $supplier['pricelists'] = $pricelists;
                return $supplier;
            });
            $data = collect($data);
            $data['suppliers'] = $suppliers;
            return $data;
        })];
    }

    public function purchaseRequest(){
        return $this->belongsTo('App\Models\PurchaseRequest','purchase_request_id','id');
    }

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function pricelist(){
        return $this->belongsTo('App\Models\Pricelist');
    }
}
