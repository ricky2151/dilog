<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    

    public function purchaseRequestDetails(){
        return $this->hasMany('App\Models\PurchaseRequestDetail');
    }
}
