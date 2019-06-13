<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CategoryPriceSelling extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name','discount'
    ];

    public function priceSellings(){
        return $this->hasMany('App\Models\PriceSelling');
    }
}
