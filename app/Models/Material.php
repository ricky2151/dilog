<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    //
    protected $fillable = [
        'goods_id', 'total','adjust','name'
    ];

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

    public function getDataAndRelation($id){
        $data = collect($this->with(
            'goods:id,name'
        )->where('id',$id)->first())->except('goods_id');

        return $data;
    }
}
