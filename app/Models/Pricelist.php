<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricelist extends Model
{
    protected $fillable = [
        'supplier_id','goods_id','price'
    ];

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    public function supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }
}
