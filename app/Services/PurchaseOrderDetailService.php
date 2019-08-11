<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\PurchaseOrderDetail;
use App\Services\PurchaseOrderService;
use App\Models\Supplier;
use App\Models\Pricelist;
use App\Models\PurchaseOrder;
use App\Models\Goods;
use Illuminate\Support\Arr;
use App\Exceptions\DatabaseTransactionErrorException;
use App\Exceptions\InvalidChangeStatusPoException;
use Illuminate\Support\Facades\DB;

class PurchaseOrderDetailService
{
    private $purchaseOrderDetail, $supplier, $purchaseOrder, $pricelist;

    public function __construct(PurchaseOrderDetail $purchaseOrderDetail, Supplier $supplier, PurchaseOrder $purchaseOrder, Pricelist $pricelist)
    {
        $this->purchaseOrderDetail = $purchaseOrderDetail;
        $this->supplier = $supplier;
        $this->purchaseOrder = $purchaseOrder;
        $this->pricelist = $pricelist;
    }

    public function handleEmptyModel(){
        if($this->purchaseOrderDetail->all()->count() === 0){
            throw new CustomModelNotFoundException("purchase_order_detail"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["purchase_order_detail"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $purchaseOrderDetail = $this->purchaseOrderDetail->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("purchase_order_detail"); 
        }
    }

    public function hanldeCreateForm($purchaseOrderId){
        $purchaseOrderService = new PurchaseOrderService($this->supplier, $this->purchaseOrder);
        $purchaseOrderService->handleInvalidParameter($purchaseOrderId);
        $purchaseOrderService->handleModelNotFound($purchaseOrderId);

        $data['purchase_order_id'] = $purchaseOrderId;
        $this->isCanCreateUpdateDelete($data, 'create');

        return $this->getGoodsWithPricelist($purchaseOrderId);
    }

    public function getGoodsWithPricelist($purchaseOrderId){
        $supplier = $this->purchaseOrder->find($purchaseOrderId)->supplier;
        if(!is_null($supplier)){
            $data = $supplier->goodsWithPricelists();
            return ["goods"=>$data];
        }
        else{
            throw new CustomModelNotFoundException("supplier"); 
        }
    }

    public function hanldeEditForm($id){
        $this->handleInvalidParameter($id);
        $this->handleModelNotFound($id);
        
        $data = $this->purchaseOrderDetail->with(['pricelist:id,price','goods:id,name','purchaseOrder:id,no_po'])->where('id', $id)->first();
        $data = collect($data)->only(['id','purchase_order','pricelist','goods','qty','subtotal','tax','discount_percent','discount_rupiah']);

        
        $item['purchase_order_id'] = $data['purchase_order']['id'];
        $this->isCanCreateUpdateDelete($item, 'edit');

        return ["purchase_order_detail"=>$data, "master_data"=> $this->getGoodsWithPricelist($data['purchase_order']['id'])];
    }

    public function hanldeStore($data){
        $this->isStoreDataValid($data);
        $data = $this->handleDiscount($data);

        DB::beginTransaction();
        try {
            $this->purchaseOrderDetail->create($data);
            $purchaseOrder = $this->purchaseOrder->find($data['purchase_order_id']);
            $purchaseOrder->setTotal();
        
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("purchase_order_detail");
        }
    }

    public function handleUpdate($data, $id){
        $this->handleInvalidParameter($id);
        $this->handleModelNotFound($id);

        $purchaseOrderDetail = $this->purchaseOrderDetail->find($id);
        $purchaseOrder = $purchaseOrderDetail->purchaseOrder;
        $data['purchase_order_id'] = $purchaseOrder['id'];

        $this->isUpdateDataValid($data);
        $data = $this->handleDiscountChooseFill($data);
        
        DB::beginTransaction();
        try {
            $purchaseOrderDetail->update($data);
            $purchaseOrderDetail->setEdited();
            $purchaseOrder->setTotal();
        
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("purchase_order_detail");
        }
    }

    public function handleDestroy($id){
        $this->handleInvalidParameter($id);
        $this->handleModelNotFound($id);

        $purchaseOrderDetail = $this->purchaseOrderDetail->find($id);
        $purchaseOrder = $purchaseOrderDetail->purchaseOrder;
        $this->isCanCreateUpdateDelete($purchaseOrderDetail, 'delete');

        DB::beginTransaction();
        try {
            $purchaseOrderDetail->delete();
            $purchaseOrder->setTotal();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("purchase_order_detail");
        }
    }

    public function handleDiscountChooseFill($data){
        if(!collect($data)->has('discount_choose')){
            $data = Arr::except($data,'discount_rupiah');
            $data = Arr::except($data,'discount_percent');
        }
        else{
            $data = $this->handleDiscount($data);
        }
        return $data;
    }

    public function isUpdateDataValid($data){
        $this->isGoodsExist($data);
        $this->isPricelistExist($data);
        $this->isCanCreateUpdateDelete($data, 'update');
    }

    public function isStoreDataValid($data){
        $this->isGoodsExist($data);
        $this->isPricelistExist($data);
        $this->isCanCreateUpdateDelete($data, 'store');
    }

    public function isCanCreateUpdateDelete($data, $condition){
        if(!$this->purchaseOrder->find($data['purchase_order_id'])->isCanEdit()){
            throw new InvalidChangeStatusPoException("$condition not new");
        }
    }

    public function isGoodsExist($data){
        if(!$this->purchaseOrder->find($data['purchase_order_id'])->isGoodsExist($data['goods_id'])){
            throw new CustomModelNotFoundException("goods"); 
        }
    }

    public function isPricelistExist($data){
        if(!$this->purchaseOrder->find($data['purchase_order_id'])->isPricelistExist($data['pricelist_id'], $data['goods_id'])){
            throw new CustomModelNotFoundException("pricelist"); 
        }
    }

    public function handleDiscount($data){
        $subtotal = $this->pricelist->find($data['pricelist_id'])['price'] * ($data['qty']);
        if($data['discount_choose']==1){
            $data['discount_rupiah'] = $subtotal *  $data['discount_percent']/100;
        }
        else{
            $data['discount_percent'] = $data['discount_rupiah']/$subtotal*100;
        }
        $data['subtotal'] = $subtotal;

        return $data;
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}