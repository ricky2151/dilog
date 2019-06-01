<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function goods(){
        return $this->belongsToMany('App\Models\Goods','category_goods','category_id','goods_id')->withTimestamps();
    }

    public function goodsStock(){
        $goods = $this->goods;

        $goods = $goods->map(function ($item) {
            $stock = collect($item->goodsRack)->sum('stock');
            return ['id' => $item['id'], 'name' => $item['name'],'stock'=>$stock];
        });

        return $goods;
    }
}
