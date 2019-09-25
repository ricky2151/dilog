<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequestDetail extends Model
{
    //Status is condition this Material Request where value 1 = accept, 0 = waiting, and -1 = rejected
    protected $fillable = [
        'material_request_id', 'goods_id','qty','status','notes','total'
    ];

    public function materialRequest(){
        return $this->belongsTo('App\Models\MaterialRequest');
    }

    public function goods(){
        return $this->belongsTo('App\Models\Goods');
    }

}
