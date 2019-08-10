<?php

namespace App\Http\Controllers\api;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePayment;
use App\Http\Requests\UpdatePayment;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    private $purchaseOrder, $payment, $paymentService;

    public function __construct(Payment $payment, PurchaseOrder $purchaseOrder, PaymentService $paymentService)
    {
        $this->payment = $payment;
        $this->purchaseOrder = $purchaseOrder;
        $this->paymentService = $paymentService;
    }
    /**
     * Display a listing of the payment.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new payment.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\StorePayment  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePayment $request)
    {
        $validated = $request->validated();
        $validated['payment_date'] = now();
        $this->payment->create($validated);

        
        return formatResponse(false,(["payment"=>["payment successfully created"]]));
    }

    /**
     * Display the specified payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified payment.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->paymentService->handleInvalidParameter($id);
        $this->paymentService->handleModelNotFound($id);
        $payment = $this->payment->find($id)->getMasterData();

        return formatResponse(false,(["payment"=>$payment]));
    }

    /**
     * approve the payment
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id){      
        $this->paymentService->handleInvalidParameter($id);
        $this->paymentService->handleModelNotFound($id);
        $this->paymentService->handleApprove($id);

        return formatResponse(false,(["payment"=>["payment was approved successfully"]]));
    }

    /**
     * Update the specified payment in storage.
     *
     * @param  $id
     * @param  \App\Models\UpdatePayment  $payment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePayment $request, $id)
    {
        $this->paymentService->handleInvalidParameter($id);
        $this->paymentService->handleModelNotFound($id);
        $this->paymentService->handleUpdate($id);

        $validated = $request->validated();
        $validated['payment_date'] = now();

        $this->payment->find($id)->update($validated);
        return formatResponse(false,(["payment"=>["payment was successfully updated"]]));
    }

    /**
     * Remove the specified payment from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->paymentService->handleInvalidParameter($id);
        $this->paymentService->handleModelNotFound($id);
        $this->paymentService->handleDelete($id);
        
        return formatResponse(false,(["payment"=>["payment deleted successfully"]]));
    }
}
