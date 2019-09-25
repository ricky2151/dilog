<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    //Status is condition this periode where value 1 = active and 0 = not active
    protected $fillable = [
        'name', 'from','to','status', 'code'
    ];

    public static function getPeriodeActive(){
        return Periode::Where("status","1")->first();
    }

    public static function getNewCode(){
        $lastId = collect(Periode::orderBy('id','desc')->first(['id']))->get('id');
        return "P-".($lastId+1);
    }

    public function materialRequests(){
        return $this->hasMany('App\Models\MaterialRequest')->orderBy('updated_at', 'desc');
    }

    public function nonActive(){
        $this->update(["status" => 0]);
    }
}
