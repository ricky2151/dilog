<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StorePurchaseOrder;
use App\Http\Requests\UpdatePurchaseOrder;
use App\Services\PurchaseOrderService;
use App\Models\PurchaseOrder;
use App\Models\Periode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseOrderController extends Controller
{
    private $purchaseOrderService, $purchaseOrder, $periode;

    public function __construct(PurchaseOrderService $purchaseOrderService, PurchaseOrder $purchaseOrder, Periode $periode)
    {
        $this->purchaseOrderService = $purchaseOrderService;
        $this->purchaseOrder = $purchaseOrder;
        $this->periode = $periode;
    }

    /**
     * Display a listing of the purchase order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $purchaseOrders = $this->purchaseOrder->latest()->get();
        $purchaseOrders = $purchaseOrders->map(function($item){
            $item['arrival'] = $item->getPoPersenComplete();
            $tax = $item->getTax();
            return [
                "id" => $item['id'],
                "no_po" => $item['no_po'],
                "arrival_percent" => $item['is_completed'],
                "total" => $item['total'],
                "payment_percent"=> $item['total'] != 0 ? $item->getTotalPayment()/$item['total']*100 : 0,
                "created_at" => $item['created_at']->format('Y-m-d'),
                "supplier_id" => $item['supplier']['id'],
                "supplier_name" => $item['supplier']['name_company'],
                "status" => $item->getNameStatusPo(),
                "tax" => $tax,
                "discount_percent" => $item->getDiskonPersen(),
                "periode" => $item['periode_id'],
                "dpp" => $item['total']-$tax,
            ];
        });
        return formatResponse(false,(["purchase_orders"=>$purchaseOrders, "periodes"=>$this->periode->all(), "periode_active"=>$this->periode->getPeriodeActive()]));
    }

    /**
     * Show the form for creating a new purchase order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $data = $this->purchaseOrderService->hanldeCreateForm();
        return formatResponse(false,($data));
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

    }

    /**
     * Display the specified purchase order .
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseOrderDetail($id)
    {
        $data = $this->purchaseOrderService->handleShowDetail($id);
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
     * get list of payment from specific purchase order
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPayments($id){
        $this->purchaseOrderService->handleInvalidParameter($id);
        $this->purchaseOrderService->handleModelNotFound($id);
        $this->purchaseOrderService->isValidOpenPayment($id);

        $purchaseOrder = $this->purchaseOrder->find($id);
        return formatResponse(false,([
            "purchase_order"=>[
                'id'=> $purchaseOrder['id'],
                'po_no'=> $purchaseOrder['no_po'],
                'po_type_name'=> $purchaseOrder->getNameTypePo(),
                'total' => $purchaseOrder['total'],
                "payments"=>$purchaseOrder->payments->sortByDesc('created_at')->values()
            ]
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->purchaseOrderService->handleInvalidParameter($id);
        $this->purchaseOrderService->handleModelNotFound($id);

        return formatResponse(false,([
            "purchase_order"=>$this->purchaseOrder->getDataAndRelation($id),
            "master_data"=>$this->purchaseOrder->getMasterData()
        ]));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $id
     * @param  \App\Models\UpdatePurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePurchaseOrder $request, $id)
    {   
        $validated = $request->validated();
        $this->purchaseOrderService->handleInvalidParameter($id);
        $this->purchaseOrderService->handleModelNotFound($id);
        $this->purchaseOrderService->handleUpdate($id, $validated);

        $this->purchaseOrder->find($id)->update($validated);
        return formatResponse(false,(["purchase_order"=>["purchase order was successfully updated"]]));
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
