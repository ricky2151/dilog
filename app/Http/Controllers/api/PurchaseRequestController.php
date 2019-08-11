<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\CreatePurchaseRequest;
use App\Services\PurchaseRequestService;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseRequestController extends Controller
{
    private $purchaseRequestService, $purchaseRequest;

    public function __construct(PurchaseRequestService $purchaseRequestService, PurchaseRequest $purchaseRequest)
    {
        $this->purchaseRequestService = $purchaseRequestService;
        $this->purchaseRequest = $purchaseRequest;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchaseRequests = $this->purchaseRequest->latest()->get();
        return formatResponse(false,(["purchase_requests"=>$purchaseRequests]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\CreatePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreatePurchaseRequest $request)
    {
        $materialRequests = $request->material_requests;
        return $this->purchaseRequestService->handleCreateForm($materialRequests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
