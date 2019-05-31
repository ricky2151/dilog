<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name'
    ];

    // public function goodsRacks(){
    //     return $this->belongsToMany('App\Models\GoodsRack','batchs','source_id','goods_rack_id')->withPivot('stock', 'batch_number')->withTimestamps();
    // }
}
