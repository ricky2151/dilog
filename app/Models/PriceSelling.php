<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PriceSelling extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'goods_id', 'warehouse_id', 'stock_cut_off','category_price_selling_id','price','free'
    ];

    // public function goodRack(){
    //     return $this->belongsTo('App\Models\Rack');
    // }
    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    public function warehouse(){
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function categoryPriceSelling(){
        return $this->belongsTo('App\Models\CategoryPriceSelling');
    }
}
