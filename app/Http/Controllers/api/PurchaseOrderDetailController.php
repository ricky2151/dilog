<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StorePurchaseOrderDetail;
use App\Http\Requests\UpdatePurchaseOrderDetail;
use App\Http\Requests\CreatePurchaseOrderDetail;
use App\Services\PurchaseOrderDetailService;
use App\Models\PurchaseOrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrderDetailController extends Controller
{
    private $purchaseOrderDetailService, $purchaseOrderDetail;

    public function __construct(PurchaseOrderDetailService $purchaseOrderDetailService, PurchaseOrderDetail $purchaseOrderDetail)
    {
        $this->purchaseOrderDetailService = $purchaseOrderDetailService;
        $this->purchaseOrderDetail = $purchaseOrderDetail;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new purchase order detail.
     *
     * @param  \Illuminate\Http\CreatePurchaseOrderDetail  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreatePurchaseOrderDetail $request)
    {
        $purchaseOrderId = $request->validated()['purchase_order_id'];
        $data = $this->purchaseOrderDetailService->hanldeCreateForm($purchaseOrderId);
        return formatResponse(false,(["purchase_order_detail"=>$data]));
    }

    /**
     * Store a newly created purchase order detail in storage.
     *
     * @param  \Illuminate\Http\StorePurchaseOrderDetail  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePurchaseOrderDetail $request)
    {
        $data = $request->validated();
        $this->purchaseOrderDetailService->hanldeStore($data);
        
        return formatResponse(false,(["purchase_order_detail"=>["purchase order detail successfully created"]]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrderDetail  $purchaseOrderDetail
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PurchaseOrderDetail $purchaseOrderDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $data = $this->purchaseOrderDetailService->hanldeEditForm($id);
        return formatResponse(false,(["purchase_order_detail"=>$data]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdatePurchaseOrderDetail  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePurchaseOrderDetail $request, $id)
    {
        $data = $request->validated();
        $this->purchaseOrderDetailService->handleUpdate($data, $id);
        
        return formatResponse(false,(["purchase_order_detail"=>["purchase order detail successfully updated"]]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->purchaseOrderDetailService->handleDestroy($id);
        return formatResponse(false,(["purchase_order_detail"=>["purchase order detail deleted successfully"]]));
    }
}
