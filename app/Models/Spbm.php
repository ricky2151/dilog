<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spbm extends Model
{

    /**
     * status = 1 untuk komplet 
     */
    protected $fillable = [
        'purchase_order_id', 'arrival_date', 'delivery_order_no', 'catatan', 'warehouse_id', 'status'
    ];

    public function warehouse(){
        return $this->belongsTo('App\Models\Warehouse');
    } 

    public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function spbmDetails(){
        return $this->hasMany('App\Models\SpbmDetail');
    }

    public function getMasterData($purchaseOrderId){
        $purchaseOrder =  PurchaseOrder::find($purchaseOrderId);
        $purchaseOrder["po_type_name"] = $purchaseOrder->getNameTypePo();
 
        $warehouseWithRacks = collect(Warehouse::with('racks')->get(['id','name']))->map(function($item){
            return ["id" => $item["id"], "name"=>$item["name"], "racks" => $item['racks']->map(function($data){
                return ["id"=>$data['id'], "name" => $data['name']];
            })];
        });

        return [
            'percent_goods'=>$purchaseOrder->getPoPersenComplete(), 
            'purchase_order'=>$purchaseOrder->only('id','no_po','po_type_name'),
            'goods'=>$orderQuantity = $purchaseOrder->getGoodsWithOrderQuantityAndHaveArrived(), 
            'warehouses' =>$warehouseWithRacks,
            'history_detail_spbm_po'=> $purchaseOrder->getHistoryArrivedGood()
        ];
    }
}
