<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Spbm;
use App\Models\PurchaseOrder;

class SpbmService
{
    private $spbm, $purchaseOrder;

    public function __construct(Spbm $spbm, PurchaseOrder $purchaseOrder)
    {
        $this->spbm = $spbm;
        $this->purchaseOrder = $purchaseOrder;
    }

    public function isValidOpenSpbm($id){
        if(!($this->purchaseOrder->find($id)->status == 3 || $this->purchaseOrder->find($id)->status == 4)){
            throw new InvalidParameterException(json_encode(["spbm"=>["can't open because po status is not approve/completed"]]));
        }
    }

    public function isValidStoreSpbm($id){
        if(!($this->purchaseOrder->find($id)->status == 3)){
            throw new InvalidParameterException(json_encode(["spbm"=>["can't store because po status is not approve"]]));
        }
    }

    public function handleEmptyModel(){
        if($this->spbm->all()->count() === 0){
            throw new CustomModelNotFoundException("spbm"); 
        } 
    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["spbm"=>["parameter invalid"]]));
        }
    }

    /**
     * Remove Data detail spbm when have quantity 0
     */
    public function cleanDataSpbmDetail($data, $purchaseOrderId){
        $goodsId = collect($this->purchaseOrder->find($purchaseOrderId)->purchaseOrderDetails)->groupBy('goods_id')->keys();
        $data = collect($data)->filter(function ($item) {
            return $item['qty'] > 0;
        })->whereIn('goods_id',$goodsId)->values();

        return $data;
    }

    public function handleModelNotFound($id){
        try{
            $spbm = $this->spbm->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("spbm"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}