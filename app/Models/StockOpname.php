<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    //approve = 0 : nothing, 1 : waiting, 2 : approve
    protected $fillable = [
        'periode','user_id','approve','approve_id','notes','warehouse_id'
    ];

    public function warehouse(){
        return $this->belongsTo("App\Models\Warehouse");
    }

    public function stockOpnameDetails(){
        return $this->hasMany("App\Models\StockOpnameDetail");
    }

    public function setWaitings(){
        return $this->update(['approve'=>'1']);
    }
}
