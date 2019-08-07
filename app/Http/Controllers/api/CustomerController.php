<?php

namespace App\Http\Controllers\api;

use App\Models\Customer;
use App\Services\CustomerService;
use App\Http\Requests\StoreCustomer;
use App\Http\Requests\UpdateCustomer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    private $customerService, $customer;

    public function __construct(CustomerService $customerService, Customer $customer)
    {
        $this->customerService = $customerService;
        $this->customer = $customer;
    }
    /**
     * Display a listing of the customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return formatResponse(false,(["customers"=>$this->customer->latest()->get()]));
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\StoreCustomer  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCustomer $request)
    {
        $data = $request->validated();
        $this->customer->create($data);

        return formatResponse(false,(["customer"=>["customer successfully created"]]));
    }

    /**
     * Display the specified customer.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->customerService->handleInvalidParameter($id);
        $this->customerService->handleModelNotFound($id);

        $customer = $this->customer->find($id);
        return formatResponse(false,(["customer"=>$customer]));
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->customerService->handleInvalidParameter($id);
        $this->customerService->handleModelNotFound($id);

        return formatResponse(false,(["customer"=>$this->customer->find($id,['id','name','no_hp','address']), "master_data"=>[]]));

    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\UpdateCustomer  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCustomer $request, $id)
    {
        $this->customerService->handleInvalidParameter($id);
        $this->customerService->handleModelNotFound($id);

        $customer = $this->customer->find($id)->update($request->validated());
        return formatResponse(false,(["customer"=>["customer was successfully updated"]]));
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->customerService->handleInvalidParameter($id);
        $this->customerService->handleModelNotFound($id);

        $this->customer->find($id)->delete();
        return formatResponse(false,(["customer"=>["customer deleted successfully"]]));
    }
}
