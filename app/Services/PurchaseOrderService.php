<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\DivisionNotPermittedException;
use App\Exceptions\InvalidChangeStatusPoException;
use App\Models\PurchaseOrder;
use Illuminate\Support\Arr;
use App\Models\Goods;
use App\Models\Supplier;
use App\Models\Periode;

class PurchaseOrderService
{
    public function handleEmptyModel(){
        if(PurchaseOrder::all()->count() === 0){
            throw new CustomModelNotFoundException("purchase_order"); 
        } 
    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["purchase_order"=>["parameter invalid"]]));
        }
    }

    public function hanldeCreateForm(){
        if(Supplier::all()->count() === 0){
            throw new CustomModelNotFoundException("supplier"); 
        } 
        else{
            return ["suppliers" => Supplier::latest()->get(), 'periode'=>Periode::getPeriodeActive(), 'no_po'=> PurchaseOrder::getNewCode()];
        }
    }

    public function handleShow($id){
        $this->handleInvalidParameter($id);
        $this->handleModelNotFound($id);
        $purchaseOrder = PurchaseOrder::find($id);
        $purchaseOrde = Arr::add($purchaseOrder, 'purchase_order_details', $purchaseOrder->purchaseOrderDetails);
        return $purchaseOrder;

    }

    public function handleApprove($id){
        $purchaseOrder = PurchaseOrder::find($id);
        $this->checkStatusPo($purchaseOrder,2);
        $purchaseOrder->approve();
    }

    public function handleSubmit($id){
        $purchaseOrder = PurchaseOrder::find($id);
        $this->checkStatusPo($purchaseOrder,1);
        $purchaseOrder->submit();
    }

    public function checkStatusPo($purchaseOrder, $condition){
        if($purchaseOrder['status'] != $condition){
            switch($condition){
                case '1' : throw new InvalidChangeStatusPoException("submit not new"); break;
                case '2' : throw new InvalidChangeStatusPoException("approve not submit"); break;
                default : break;
            }
        }
        
    }

    public function handleStore($data){
        $data = Arr::Add($data, 'periode_id',Periode::getPeriodeActive()['id']);
        $data = Arr::Add($data, 'no_po',PurchaseOrder::getNewCode());
        $data = $this->handlePaymentType($data);
        $data = $this->handleType($data);

        // return $data;
        PurchaseOrder::create($data);
    }

    public function handleType($data){
        // 2 untuk PO PR
        if($data['type'] == 2){
            return $data;
        }
        // 1 & 3 untuk PO langsung dan PO min
        else{
            return Arr::except($data,['purchase_request_id']);
        }
    }

    public function handlePaymentType($data){
        // 1 untuk tempo
        if($data['payment_type'] == 1){
            return $data;
        }
        // 2 untuk tunai
        else{
            return Arr::except($data,['payment_terms']);
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = PurchaseOrder::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("purchase_order"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}