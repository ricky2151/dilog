<?php

namespace App\Http\Controllers\api;


use App\Services\SupplierService;
use App\Http\Requests\StoreSupplier;
use App\Http\Requests\UpdateSupplier;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    private $supplierService, $supplier;

    public function __construct(SupplierService $supplierService, Supplier $supplier)
    {
        $this->supplierService = $supplierService;
        $this->supplier = $supplier;
    }
    /**
     * Display a listing of the Supplier.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->supplierService->handleEmptyModel();

        return formatResponse(false,(["suppliers"=>$this->supplier->latest()->get(['id','name_company','name_pic','address'])]));
    }

    /**
     * Show the form for creating a new Supplier.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return formatResponse(false,($this->supplier->getMasterData()));
    }

    /**
     * Store a newly created Supplier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSupplier $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $pricelists = collect(Arr::pull($data,'pricelists'))->toArray();

            $supplier = $this->supplier->create($data);
            $supplier->pricelists()->createMany($pricelists);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("Supplier");
        }

        return formatResponse(false,(["Supplier"=>["Supplier successfully created"]]));

    }

    /**
     * Display the specified Supplier.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->supplierService->handleInvalidParameter($id);
        $this->supplierService->handleModelNotFound($id);

        $supplier = $this->supplier->find($id);
        
        return formatResponse(false,(["supplier"=>$supplier]));
    }

    /**
     * Show the form for editing the specified Supplier.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->supplierService->handleInvalidParameter($id);
        $this->supplierService->handleModelNotFound($id);


        return formatResponse(false,(["supplier"=>$this->supplier->getDataAndRelation($id), 'master_data'=>$this->supplier->getMasterData()]));
    }

    /**
     * Update the specified Supplier in storage.
     *
     * @param  \Illuminate\Http\UpdateSupplier  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSupplier $request, $id)
    {
        $this->supplierService->handleInvalidParameter($id);
        $this->supplierService->handleModelNotFound($id);

        $data = $request->validated();

        $supplier = $this->supplier->find($id);
        DB::beginTransaction();
        try {
            $pricelists = collect(Arr::pull($data,'pricelists'))->toArray();

            $supplier->update($data);
            $supplier->pricelists()->createMany($pricelists);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("Supplier");
        }

        return formatResponse(false,(["supplier"=>["Supplier successfully updated"]]));
    }

    /**
     * Remove the specified Supplier from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->supplierService->handleInvalidParameter($id);
        $this->supplierService->handleModelNotFound($id);

        DB::beginTransaction();
        try {
            // $this->supplier->pricelists($id)->goods()->sync([]);
            $this->supplier->find($id)->delete();

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Supplier");
        }

        return formatResponse(false,(["supplier"=>["Supplier deleted successfully"]]));
    }
}
