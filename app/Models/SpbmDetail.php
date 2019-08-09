<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpbmDetail extends Model
{
    protected $fillable = [
        'spbm_id', 'goods_id', 'qty', 'notes', 'defective_qty ', 'rack_id'
    ];

    public function spbm(){
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    public function rack(){
        return $this->belongsTo('App\Models\Rack');
    }

    public function getGoodsArrived(){
        return $this->groupBy('goods_id')->map(function($item){
            return $item->sum('qty');
        });
    }
}
