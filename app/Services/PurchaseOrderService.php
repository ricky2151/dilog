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
    private $supplier, $purchaseOrder, $user;

    public function __construct(Supplier $supplier, PurchaseOrder $purchaseOrder)
    {
        $this->supplier = $supplier;
        $this->purchaseOrder = $purchaseOrder;
        $this->user = auth('api')->user();
    }

    public function handleEmptyModel(){
        if($this->purchaseOrder->all()->count() === 0){
            throw new CustomModelNotFoundException("purchase_order"); 
        } 
    }

    public function isValidOpenPayment($id){
        if(!($this->purchaseOrder->find($id)->status == 3 || $this->purchaseOrder->find($id)->status == 4)){
            throw new InvalidParameterException(json_encode(["purchase_order"=>["can't open payment because status is not approve/complete"]]));
        } 
    }

    public function handleUpdate($id, $data){
        $purchaseOrder = $this->purchaseOrder->find($id);
        if(!$purchaseOrder->purchaseOrderDetails->isEmpty()){
            if($data['supplier_id'] != $purchaseOrder['supplier_id']){
                throw new InvalidParameterException(json_encode(["purchase_order"=>["can't update supplier because PO detail have been created"]]));
            }
        }
    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["purchase_order"=>["parameter invalid"]]));
        }
    }

    public function hanldeCreateForm(){
        if($this->supplier->all()->count() === 0){
            throw new CustomModelNotFoundException("supplier"); 
        } 
        else{
            return $this->purchaseOrder->getMasterData();
        }
    }

    public function handleShowDetail($id){
        $this->handleInvalidParameter($id);
        $this->handleModelNotFound($id);

        $purchaseOrder = $this->purchaseOrder->find($id,['id','no_po','type','total','created_by_user_id','status']);
        $purchaseOrder = Arr::add($purchaseOrder, 'status_name', $purchaseOrder->getNameStatusPo());
        $purchaseOrder = Arr::add($purchaseOrder, 'created_by_user_name', $purchaseOrder->createdByUser->name);
        $purchaseOrder = Arr::add($purchaseOrder, 'type_name', $purchaseOrder->getNameTypePo());
        $purchaseOrder = Arr::add($purchaseOrder, 'purchase_order_details', $purchaseOrder->purchaseOrderDetails);
        $purchaseOrder->purchase_order_details = $purchaseOrder->purchase_order_details->map(function($item){
            $item['goods_name'] = Goods::find($item['goods_id'])['name'];
            return $item;
        });

        return collect($purchaseOrder)->except(['created_by_user']);

    }

    public function handleApprove($id){
        $purchaseOrder = $this->purchaseOrder->find($id);
        $this->checkStatusPo($purchaseOrder,2);
        $purchaseOrder->approve();
    }

    public function handleSubmit($id){
        $purchaseOrder = $this->purchaseOrder->find($id);
        if($purchaseOrder->purchaseOrderDetails->isEmpty()){
            throw new InvalidParameterException(json_encode(["purchase_order"=>["can't submit because PO detail is null"]]));
        }
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
        $data = $this->handlePaymentType($data);
        $data = $this->handleType($data);
        $this->user->purchaseOrdersCreated()->create($data);
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
            $purchaseOrder = $this->purchaseOrder->findOrFail($id);
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