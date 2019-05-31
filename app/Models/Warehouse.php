<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Warehouse extends Model
{
    use SoftDeletes;

    public function updateManyAtribut($racks_update){
        if(!is_null($racks_update)){
            foreach ($racks_update as $update) {
                $data = Arr::except($update, ['id']);
                $this->racks()->find($update["id"])->update($data);
            }
        }
    }

    public function deleteManyAtribut($racks_delete){
        if(!is_null($racks_delete)){
            foreach ($racks_delete as $delete) {
                $this->racks()->find($delete["id"])->delete();
            }
        }
    }

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

    public function priceSelling(){
        return $this->hasMany('App\Models\PriceSelling')->orderBy('updated_at', 'desc');
    }
}
