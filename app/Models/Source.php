<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    //
    protected $fillable = [
        'name'
    ];

    public function goodRacks(){
        return $this->belongsToMany('App\Models\GoodRack','batch','source_id','good_rack_id')->withPivot('stock', 'batch_number')->withTimestamps();
    }
}
