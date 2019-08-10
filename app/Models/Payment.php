<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //Status is condition this payment where value 1 = approve, 0 = new
    protected $fillable = [
        'id', 'purchase_order_id','payment_date', 'paid_off', 'status','approved_by_user_id'
    ];

    public function purchaseOrder(){
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function getMasterData(){
        $payment = $this;
        $payment['no_po'] = $payment->purchaseOrder->no_po;
        return $payment->only(['id','payment_date','paid_off','no_po']);
    }

    public function approve(){
        $this->status = '1';
        $this->approved_by_user_id = auth('api')->user()->id;
        $this->save();
    }
}
