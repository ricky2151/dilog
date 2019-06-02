<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UpdateWarehouse;
use App\Http\Requests\StoreWarehouse;
use App\Services\WarehouseService;
use App\Exceptions\DatabaseTransactionErrorException;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Image;
use Storage;
use File;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    private $warehouseService, $warehouse,$path;

    public function __construct(WarehouseService $warehouseService, Warehouse $warehouse)
    {
        $this->warehouseService = $warehouseService;
        $this->warehouse = $warehouse;
        $this->path = 'warehouse';
    }
    /**
     * Display a listing of the Warehouse.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->warehouseService->handleEmptyModel();
        $warehouses = $this->warehouse->latest()->get();
        return formatResponse(false,(["warehouse"=>$warehouses]));
    }

    /**
     * Display a listing of the rack in specific warehouse.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function racks($id)
    {
        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);

        $racks = $this->warehouse->find($id)->getRackWithHaveGoods();

        return formatResponse(false,(["warehouse"=>$racks]));
    }

    /**
     * Display a listing of the goodsRack in specific warehouse.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function goodsRacks($id)
    {
        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);

        $racks = $this->warehouse->find($id)->getGoodsRack();

        return formatResponse(false,(["warehouse"=>$racks]));
    }

    /**
     * Store a newly created Warehouse in storage.
     *
     * @param  \Illuminate\Http\StoreWarehouse  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreWarehouse $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $this->warehouseService->handleCreate($data);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("Warehouse");
        }

        return formatResponse(false,(["warehouse"=>["Warehouse successfully created"]]));
    }

    /**
     * Display the specified Warehouse.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);

        $warehouse = $this->warehouse->find($id);

        return formatResponse(false,(["warehouse"=>$warehouse]));
    }

    /**
     * Show the form for editing the specified Warehouse.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);

        $warehouse = $this->warehouse->find($id);
        $warehouse = collect($warehouse);
        
        $concatenated = $warehouse->union(["racks"=>$this->warehouse->find($id)->racks]);

        return formatResponse(false,(["warehouse"=>$concatenated]));
    }
    /**
     * Update the specified Warehouse in storage.
     *
     * @param  \Illuminate\Http\UpdateWarehouse  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateWarehouse $request, $id)
    {
        $data = $request->validated();

        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);

        $warehouse = $this->warehouse->find($id);

        DB::beginTransaction();
        try {
            $racks = collect(Arr::pull($data,'racks'))->unique(function ($item) {
                return $item['name'];
            })->toArray();

            $warehouse->update($data);
            is_null($racks) ? "" : $warehouse->updateRack($racks);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("Warehouse");
        }

        
        
        return formatResponse(false,(["warehouse"=>["warehouse was successfully updated"]]));
    }

    /**
     * Remove the specified Warehouse from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);
        DB::beginTransaction();
        try {
            $this->warehouse->find($id)->racks()->delete();
            $this->warehouse->find($id)->delete();

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Warehouse");
        }
        return formatResponse(false,(["warehouse"=>["warehouse deleted successfully"]]));
    }
}
