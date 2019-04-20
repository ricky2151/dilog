<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $fillable = [
        'name', 'address','lat','lng','telp','email','pic'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
