<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    /**
     * subtotal = harga * qty
     */
    protected $fillable = [
        'purchase_order_id', 'pricelist_id', 'goods_id', 'qty', 'subtotal', 'tax', 'discount_percent', 'discount_rupiah', 'is_edited'
    ];

    public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function pricelist(){
        return $this->belongsTo('App\Models\Pricelist');
    }

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    public function setEdited(){
        $this->is_edited = true;
        $this->save();
    }
}
