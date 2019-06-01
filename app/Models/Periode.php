<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    //Status is condition this periode where value 1 = active and 0 = not active
    protected $fillable = [
        'name', 'from','to','status'
    ];

    public function materialRequests(){
        return $this->hasMany('App\Models\MaterialRequest')->orderBy('updated_at', 'desc');
    }

    public function nonActive(){
        $this->update(["status" => 0]);
    }
}
