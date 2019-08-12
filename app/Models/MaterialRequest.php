<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequest extends Model
{
    //Status is condition this Material Request where value 1 = approved, 0 = new
    protected $fillable = [
        'code', 'division_id','request_by_user_id','status','periode_id'
    ];

    public function updateDetailMaterialRequest($materialRequestDetails){
        foreach($materialRequestDetails as $materialRequestDetail){
            if($materialRequestDetail['type'] == 1) {
                $this->materialRequestDetails()->create($materialRequestDetail);
            }
            else if($materialRequestDetail['type'] == 0) {
                $this->materialRequestDetails()->find($materialRequestDetail['id'])->update($materialRequestDetail);
            } else {
                $this->materialRequestDetails()->find($materialRequestDetail['id'])->delete();
            }
        }
    }

    public static function getMaterialRequestInActivePeriode(){
        return auth('api')->user()->division->materialRequest->where('periode_id',Periode::getPeriodeActive()['id'])->flatten(1);
    }

    public function getTotalGoods(){
        
    }

    public function approve(){
        $this->update(['status'=>1]);
    }

    public function period(){
        return $this->belongsTo('App\Models\Period');
    }

    public function materialRequestDetails(){
        return $this->hasMany('App\Models\MaterialRequestDetail');
    }

    public function division(){
        return $this->belongsTo('App\Models\Division');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','request_by_user_id','id');
    }
}
