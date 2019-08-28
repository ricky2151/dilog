<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Payment;
use App\Models\PurchaseOrder;

class PaymentService
{
    private $payment, $purchaseOrder;

    public function __construct(Payment $payment, PurchaseOrder $purchaseOrder)
    {
        $this->payment = $payment;
        $this->purchaseOrder = $purchaseOrder;
    }

    public function handleEmptyModel(){
        if($this->payment->all()->count() === 0){
            throw new CustomModelNotFoundException("payment"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["payment"=>["parameter invalid"]]));
        }
    }

    public function statusApprovePo($purchaseOrder){
        if($purchaseOrder['status']!=3){
            throw new InvalidParameterException(json_encode(["payment"=>["payment cannot be update/read/create because the status of PO not approved"]]));
        }
    } 

    public function handleApprove($id){
        $payment = $this->payment->find($id);
        $purchaseOrder = $payment->purchaseOrder;
        $this->statusApprovePo($purchaseOrder);
        if($payment->status == 1){
            throw new InvalidParameterException(json_encode(["payment"=>["payment cannot be approved because the previous status has been approved"]]));
        }
        else{
            $payment->approve();
        }
    }
    
    public function handleStore($purchaseOrderId){
        $purchaseOrder = $this->purchaseOrder->find($purchaseOrderId);
        $this->statusApprovePo($purchaseOrder);
        if(!($purchaseOrder->status == 3 || $purchaseOrder->status == 4)){
            throw new InvalidParameterException(json_encode(["payment"=>["payment cannot be stored because the status PO not approve/complete"]]));
        }
    }

    public function handleUpdate($id){
        $payment = $this->payment->find($id);
        $purchaseOrder = $payment->purchaseOrder;
        $this->statusApprovePo($purchaseOrder);
        if($payment->status == 1){
            throw new InvalidParameterException(json_encode(["payment"=>["payment cannot be update because the status has been approved"]]));
        }
    }

    public function handleDelete($id){
        $payment = $this->payment->find($id);
        $purchaseOrder = $payment->purchaseOrder;
        $this->statusApprovePo($purchaseOrder);
        if($payment->status == 1){
            throw new InvalidParameterException(json_encode(["payment"=>["payment cannot be delete because the status has been approved"]]));
        }
        else{
            $payment->delete();
        }
    }

    public function handleModelNotFound($id){
        try{
            $payment = $this->payment->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("payment"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}