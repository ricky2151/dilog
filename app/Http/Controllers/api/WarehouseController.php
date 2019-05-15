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
            $racks = Arr::pull($data,'racks');

            $warehouse = $this->warehouse->create($data);
            $warehouse->racks()->createMany($racks);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
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

        $racks_delete = Arr::pull($data,'racks_delete');
        $racks_update = Arr::pull($data,'racks_update');
        
        $this->warehouseService->checkRelationship($id,collect($racks_delete)->pluck("id"));
        $this->warehouseService->checkRelationship($id,collect($racks_update)->pluck("id"));

        $warehouse = $this->warehouse->find($id);

        DB::beginTransaction();
        try {
            $racks_new = Arr::pull($data,'racks_new');

            $warehouse->update($data);
            is_null($racks_new) ? "" : $warehouse->racks()->createMany($racks_new);
            $warehouse->updateManyAtribut($racks_update);
            $warehouse->deleteManyAtribut($racks_delete);

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
