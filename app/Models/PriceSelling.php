<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSelling extends Model
{
    //
    protected $fillable = [
        'good_rack_id', 'stock_cut_off','category_price_selling_id','price','discount','free'
    ];

    public function goodRack(){
        return $this->belongsTo('App\Models\Rack');
    }

    public function categoryPriceSelling(){
        return $this->belongsTo('App\Models\CategoryPriceSelling');
    }
}
