<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPriceSelling extends Model
{
    //
    protected $fillable = [
        'name'
    ];

    public function priceSellings(){
        return $this->hasMany('App\Models\PriceSelling');
    }
}
