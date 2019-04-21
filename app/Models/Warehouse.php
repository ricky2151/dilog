<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $fillable = [
        'name', 'address','lat','lng','telp','email','pic'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

    public function racks(){
        return $this->hasMany('App\Models\Rack');
    }
}
