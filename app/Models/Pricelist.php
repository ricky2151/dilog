<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricelist extends Model
{
    protected $fillable = [
        'supplier_id','goods_id','price'
    ];
}
