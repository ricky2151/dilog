<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestDetail extends Model
{
    //Status is condition this Material Request where value 1 = responded, 0 = not yet responded
    protected $fillable = [
        'purchase_request_id', 'goods_id','qty', 'notes', 'pricelist_id', 'price', 'supplier_id', 'is_created_as_po'
    ];

    public function purchaseRequest(){
        return $this->belongsTo('App\Models\PurchaseRequest');
    }
}
