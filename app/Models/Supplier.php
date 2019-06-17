<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name_company','name_owner','name_pic','name_sales','address','no_telp_company','no_telp_owner','email','fax','npwp','no_rek'
    ];

    public static function allDataCreate(){
        return ['goods' => Goods::all(['id','name'])];
    }

    public function pricelists(){
        return $this->hasMany('App\Models\Pricelist');
    }

}
