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

        $suppliers = $this->supplier->latest()->get();
        return formatResponse(false,(["suppliers"=>$suppliers]));
    }

    /**
     * Show the form for creating a new Supplier.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return formatResponse(false,([$this->supplier->allDataCreate()]));
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
            $goods = collect(Arr::pull($data,'pricelists'));

            $supplier = $this->supplier->create($data);
            $supplier->goods()->attach($goods);

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

        $allGoods = collect($this->supplier->allDataCreate());

        $supplier = $this->supplier->find($id);
        $supplier = collect($supplier);
        
        $concatenated = $supplier->union($allGoods)->union($this->showFormatData($id));

        return formatResponse(false,(["supplier"=>$concatenated]));
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
            $goods = collect(Arr::pull($data,'pricelists'));

            $supplier->update($data);
            $supplier->goods()->sync($goods);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("Supplier");
        }

        return formatResponse(false,(["Supplier"=>["Supplier successfully updated"]]));
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
            $this->supplier->find($id)->goods()->sync([]);
            $this->supplier->find($id)->delete();

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Supplier");
        }

        return formatResponse(false,(["Supplier"=>["Supplier deleted successfully"]]));
    }

    /**
     * Format supplier relation data in method show.
     *
     * @param  $id
     * @return Illuminate\Support\Collection
     */
    public function showFormatData($id){
        $goods = $this->supplier->find($id)->goods;

        $goods = $goods->map(function ($item) { 
            $goods = Arr::add($item['pivot'], 'goods_name', $item['name']);
            return $goods;
        });

        $data = collect(["pricelists" => $goods]);

        return $data;
    }
}
