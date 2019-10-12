<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\DivisionNotPermittedException;
use App\Models\PurchaseRequestDetail;
use App\Models\MaterialRequest;
use Illuminate\Support\Arr;
use App\Models\Goods;
use App\Models\Periode;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;

class PurchaseRequestDetailService
{
    private $purchaseRequestDetail, $user;

    public function __construct(PurchaseRequestDetail $purchaseRequestDetail)
    {
        $this->purchaseRequestDetail = $purchaseRequestDetail;
        $this->user = auth('api')->user()->makeVisible('id')->toArray();
    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["purchase_request_details"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = $this->purchaseRequestDetail->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("purchase_request_details"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}