<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\DivisionNotPermittedException;
use App\Models\PurchaseRequest;
use App\Models\MaterialRequest;
use Illuminate\Support\Arr;
use App\Models\Goods;
use App\Models\Periode;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;

class PurchaseRequestService
{
    private $purchaseRequest, $materialRequest, $periode, $user;

    public function __construct(PurchaseRequest $purchaseRequest, MaterialRequest $materialRequest, Periode $periode)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->materialRequest = $materialRequest;
        $this->periode = $periode;
        $this->user = auth('api')->user()->makeVisible('id')->toArray();
    }

    public function handleEmptyModel(){
        if($this->purchaseRequest->all()->count() === 0){
            throw new CustomModelNotFoundException("purchase_request"); 
        } 
    }

    public function getRekapsFromPr($id){
        return $this->purchaseRequest->find($id)->getRekapDatas();
    }

    public function handleStoreToPo($supplierId, $id){
        $purchaseRequest = $this->purchaseRequest->find($id);
        $purchaseRequestDetails = $purchaseRequest->purchaseRequestDetailsNotYetBePo()->where('supplier_id',$supplierId)->values();
        // return $this->user['id'];

        if($purchaseRequestDetails->isEmpty()){
            throw new InvalidParameterException(json_encode(["purchase_request"=>["material requests is null"]]));
        }

        DB::beginTransaction();
        try {
            $purchaseOrder = $purchaseRequest->purchaseOrders()->create([
                "no_po"=> 'POPR-'.date('isHYmd'),
                "created_by_user_id" => $this->user['id'], 
                "supplier_id"=>$supplierId,
                "payment_type" => 2,
                "type" => 2,
                "status"=>2,
                "periode_id" => $this->periode->getPeriodeActive()['id']
            ]);

            $purchaseRequestDetails->map(function($item) use($purchaseOrder){
                $data = [
                    'pricelist_id'=>$item['pricelist_id'],
                    'goods_id'=>$item['goods_id'],
                    'qty'=>$item['qty'],
                    'pricelist_id'=>$item['pricelist_id'],
                    'subtotal'=>$item['pricelist']['price'] * $item['qty'],
                    'tax' => 0,
                    'discount_percent' => 0,
                    'discount_rupiah'=> 0
                ];
                $purchaseOrder->purchaseOrderDetails()->create($data);
                $item->setCreatedPo();
            });

            $purchaseRequest->setComplete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("purchase_request");
        }

    }

    public function getRekapFromMateril($materialRequests){        
        $data = collect($materialRequests)->map(function($item){
            $item = $this->materialRequest->find($item['id'])->materialRequestDetails;
            return $item;
        })->flatten(1)->groupBy('goods_id')->map(function($key, $item){
            $goods = Goods::find($item);
            return [
                "goods_id" => $item, 
                'goods_name'=>$goods['name'],
                'total_required_by_mr' => $key->sum('qty'),
                'stock'=> $goods->stock(),
                'total_already_po' => 0,
                'suppliers' => $goods->suppliers->unique(function ($item) {
                    return $item['id'];
                })->flatten(1)->map(function($item){
                    return Arr::except($item,['pivot']);
                }),
                'pricelists' => $goods->pricelists    
            ];
        })->sortBy('goods_id')->values();

        return $data;
    }

    public function handleStore($materialRequests){
        if(empty($materialRequests)){
            throw new InvalidParameterException(json_encode(["purchase_request"=>["material requests is null"]]));
        }
    }

    public function storePurchaseRequestDetails($purchaseRequestDetails){
        if(empty($purchaseRequestDetails)){
            throw new InvalidParameterException(json_encode(["purchase_request"=>["purchase request details is null"]]));
        }
    }

    public function setMrProcess($data){
        if($data['status']!=1){
            throw new InvalidParameterException(json_encode(["purchase_request"=>["MR status not approve"]]));
        }
    }

    public function handleCreateForm(){
        $data = ["material_requests" => $materialRequests = collect($this->materialRequest->latest()->get())->where('status','0')->values()->map(function($item){
            return[
                'id' => $item['id'],
                'no_mr' => $item['no_mr'],
                'division' => $item->division->name,
                'created_at' => $item['created_at']->format('Y-m-d'),
                'periode_id' => $item['periode_id']
            ];
        }), "periode_active"=>$this->periode->getPeriodeActive(), "periodes"=>$this->periode->latest()->get()];

        return formatResponse(false,($data));
    }
    

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["purchase_request"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = $this->purchaseRequest->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("purchase_request"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}