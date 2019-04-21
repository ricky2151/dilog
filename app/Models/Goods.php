<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    //
    protected $fillable = [
        'uuid', 'name','code','desc','margin','value','status','last_buy_pricelist','barcode_master','thumbnail','avgprice_status','user_id','tax','unit_id','cogs_id'
    ];

    public function goodsRack(){
        return $this->hasMany('App\Models\GoodRack');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function unit(){
        return $this->belongsTo('App\Models\Unit');
    }

    public function cogs(){
        return $this->belongsTo('App\Models\Cogs');
    }

    public function categories(){
        return $this->belongsToMany('App\Models\Category','category_goods','goods_id','category_id')->withTimestamps();
    }

    public function attributes(){
        return $this->belongsToMany('App\Models\Attribute','attribute_goods','goods_id','attribute_id')->withPivot('value')->withTimestamps();
    }

    public function materials(){
        return $this->hasMany('App\Models\Material');
    }
}
