<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekap extends Model
{
    protected $fillable = [
        'purchase_request_id', 'goods_id', 'total_required_by_mr'
    ];

    public function purchaseRequest(){
        return $this->belongsTo('App\Models\PurchaseRequest');
    }
}
