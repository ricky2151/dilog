<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\StorePurchaseRequestDetail;
use App\Http\Requests\StorePurchaseRequestToPurchaseOrder;
use App\Services\PurchaseRequestService;
use App\Models\PurchaseRequest;
use App\Models\MaterialRequest;
use App\Models\Periode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;

class PurchaseRequestController extends Controller
{
    private $purchaseRequestService, $purchaseRequest, $materialRequest, $user, $periode;

    public function __construct(PurchaseRequestService $purchaseRequestService, PurchaseRequest $purchaseRequest, MaterialRequest $materialRequest, Periode $periode)
    {
        $this->purchaseRequestService = $purchaseRequestService;
        $this->purchaseRequest = $purchaseRequest;
        $this->materialRequest = $materialRequest;
        $this->periode = $periode;
        $this->user = auth('api')->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $purchaseRequests = $this->purchaseRequest->latest()->get()->map(function($item){
            $item['status_name'] = $item->prGetStatusName();
            $item['created_by_user_name'] = $item['user']['name'];
            $item['periode'] = $item['periode_id'];
            return collect($item)->except('user','periode_id');
        });
        return formatResponse(false,([
            "purchase_requests"=>$purchaseRequests, 
            'periode_active'=> $this->periode->getPeriodeActive(),
            'periodes'=> $this->periode->all()
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return $this->purchaseRequestService->handleCreateForm();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StorePurchaseRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePurchaseRequest $request)
    {
        $validated = $request->validated();
        $this->purchaseRequestService->handleStore($validated);
        $data = $this->purchaseRequestService->getRekapFromMateril($validated['material_requests']);
        $mr = $validated['material_requests'];
        DB::beginTransaction();
        try {
            collect($mr)->map(function($item){
                $item = $this->materialRequest->find($item)[0];
                // $this->purchaseRequestService->setMrProcess($item);
                $item->setProcess();
            });

            $rekaps = $data->map(function($item){
                return [
                    'goods_id' => $item['goods_id'],
                    'total_required_by_mr' => $item['total_required_by_mr']
                ];
            })->toArray();

            $purchaseRequest = $this->user->purchaseRequests()->create(["code"=>"","periode_id"=>$this->periode->getPeriodeActive()['id']]);
            $purchaseRequest->update(["code"=>"PR-".$purchaseRequest['id']]);
            $purchaseRequest->rekaps()->createMany($rekaps);
            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("purchase_request");
        }
        return formatResponse(false,(["purchase_request"=> $purchaseRequest,"rekaps"=>$data]));
    }

    /**
     * Store a newly created purchase request detail on specific purchase request in storage.
     *
     * @param  \Illuminate\Http\StorePurchaseRequest  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePurchaseRequestDetails(StorePurchaseRequestDetail $request, $id)
    {
        $this->purchaseRequestService->handleInvalidParameter($id);
        $this->purchaseRequestService->handleModelNotFound($id);
        $validated = $request->validated();
        $this->purchaseRequestService->storePurchaseRequestDetails($validated);

        DB::beginTransaction();
        try {
            $purchaseRequestDetails = collect($validated)->values()->flatten(1)->toArray();
            $purchaseRequest = $this->purchaseRequest->find($id);
            $purchaseRequestDetails = $purchaseRequest->purchaseRequestDetails()->createMany($purchaseRequestDetails);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("purchase_request");
        }

        return formatResponse(false,(["rekap_purchase_orders"=>$purchaseRequest->getRekapsToPo()]));
    }

    /**
     * Display list Purchase Request Details who have not yet become PO of the specified purchase request.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseRequestDetailsToPurchaseOrder($id){
        $this->purchaseRequestService->handleInvalidParameter($id);
        $this->purchaseRequestService->handleModelNotFound($id);
        $purchaseRequest = $this->purchaseRequest->find($id);

        return formatResponse(false,(["rekap_purchase_orders"=>$purchaseRequest->getRekapsToPo()]));
    }

    /**
     * Display list Purchase Request Details of the specified purchase request.
     *
     * @param  \Illuminate\Http\StorePurchaseRequestToPurchaseOrder  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeToPurchaseOrders(StorePurchaseRequestToPurchaseOrder $request, $id){
        $this->purchaseRequestService->handleInvalidParameter($id);
        $this->purchaseRequestService->handleModelNotFound($id);
        
        $this->purchaseRequestService->handleStoreToPo($request->validated()['supplier_id'], $id);
        return formatResponse(false,(["purchase_request"=> "successfully make po from pr"]));
    }

    /**
     * Display list Purchase Request Details of the specified purchase request.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseRequestDetails($id){
        $this->purchaseRequestService->handleInvalidParameter($id);
        $this->purchaseRequestService->handleModelNotFound($id);
        $purchaseRequest = $this->purchaseRequest->find($id);

        return formatResponse(false,(["purchase_request_details"=>$purchaseRequest->getDataPurchaseRequestDetails()]));
    }

    /**
     * Display list rekaps of the specified purchase request.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rekaps($id){
        $this->purchaseRequestService->handleInvalidParameter($id);
        $this->purchaseRequestService->handleModelNotFound($id);
        $data = $this->purchaseRequestService->getRekapsFromPr($id);
        return formatResponse(false,(["rekaps"=>$data]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        //
    }
}
