<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $fillable = [
        'stock_opname_id','goods_id','current_stock','new_stock','notes'
    ];

    public function stockOpname(){
        return $this->belongsTo("App\Models\StockOpname");
    }

    public function goods(){
        return $this->belongsTo("App\Models\Goods");
    }
}
