<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /**
     * payment_type = 1 untuk tempo, 2 untuk tunai
     * type = 1 untuk PO langsung, 2 untuk PO PR, 3 untuk PO min
     * payment_terms = lama tempo
     * status = 1 untuk New, 2 untuk Submitted, 3 untuk Approved, 4 untuk Finish
     * is_completed = untuk berapa persen jumlah PO yang sudah datang (ex : barang datang/pesanan total * 100%. Contoh 20/100 = 20%)
     */
    protected $fillable = [
        'supplier_id', 'finish_date', 'paid_date', 'fine', 'payment_type', 'type', 'payment_terms', 'periode_id', 'approved_by_user_id', 'status', 'total', 'po_no', 'is_completed', 'purchase_request_id','no_po'
    ];

    public static function getNewCode(){
        $lastId = collect(PurchaseOrder::orderBy('id','desc')->first(['id']))->get('id');
        return "PO-".($lastId+1);
    }

    public function purchaseOrderDetails(){
        return $this->hasMany('App\Models\PurchaseOrderDetail');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function approve(){
        $this->status = 3;
        $this->save();
    }

    public function submit(){
        $this->status = 2;
        $this->save();
    }

    public function setTotal(){
        $this->total = $this->getTotal();
        $this->save();
    }

    public function isCanEdit(){
        return $this->status == 1? true : false;
    }

    public function isGoodsExist($goodsId){
        $condition = $this->supplier->pricelists->groupBy('goods_id')->keys()->search($goodsId);
        if(is_numeric($condition)){
            return true;
        }
        else{
            return false;
        }
    }

    public function isPricelistExist($pricelistId, $goodsId){
        $condition = $this->supplier->pricelists->where('goods_id',$goodsId)->firstWhere('id',$pricelistId);
        if(!is_null($condition)){
            return true;
        }
        else{
            return false;
        }
    }

    public function getTotal(){
        return $purchaseOrderDetails = $this->purchaseOrderDetails->sum(function ($item) {
            $newSubTotal = ($item['subtotal']-$item['discount_rupiah']);
            return $newSubTotal - $newSubTotal*($item['tax']/100);
        });
    }

    
}
