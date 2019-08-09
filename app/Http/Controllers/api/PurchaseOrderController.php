<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StorePurchaseOrder;
use App\Services\PurchaseOrderService;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrderController extends Controller
{
    private $purchaseOrderService, $purchaseOrder;

    public function __construct(PurchaseOrderService $purchaseOrderService, PurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrderService = $purchaseOrderService;
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * Display a listing of the purchase order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $purchaseOrders = $this->purchaseOrder->latest()->get();
        return formatResponse(false,(["purchase_orders"=>$purchaseOrders]));
    }

    /**
     * Show the form for creating a new purchase order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $data = $this->purchaseOrderService->hanldeCreateForm();
        return formatResponse(false,(["purchase_order"=>$data]));
    }

    /**
     * Store a newly created purchase order in storage.
     *
     * @param  \Illuminate\Http\StorePurchaseOrder  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePurchaseOrder $request)
    {
        $this->purchaseOrderService->handleStore($request->validated());
        return formatResponse(false,(["purchase_order"=>["purchase order successfully created"]]));

    }

    /**
     * Display the specified purchase order.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->purchaseOrderService->handleShow($id);
        return formatResponse(false,(["purchase_order"=>$data]));
    }

    /**
     * Change status purchase order from submit to approve.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id)
    {
        $this->purchaseOrderService->handleInvalidParameter($id);
        $this->purchaseOrderService->handleModelNotFound($id);
        $this->purchaseOrderService->handleApprove($id);
        
        return formatResponse(false,(["purchase_order"=>["PO has been approved"]]));
    }

    /**
     * Change status purchase order from new to submit.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit($id)
    {
        $this->purchaseOrderService->handleInvalidParameter($id);
        $this->purchaseOrderService->handleModelNotFound($id);
        $this->purchaseOrderService->handleSubmit($id);
        
        return formatResponse(false,(["purchase_order"=>["PO has been submitted"]]));
    }

    /**
     * Change status purchase order from new to submit.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function spbm($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
