<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UpdateWarehouse;
use App\Http\Requests\StoreWarehouse;
use App\Services\WarehouseService;
use App\Models\Warehouse;
use Illuminate\Http\Request;
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
        $warehouses = $this->warehouse->all();
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

        $path = $this->warehouseService->handleUploadImage($request->file("pic"),$this->path,$request["name"]);
        $data["pic"] = $path;

        $this->warehouse->create($data);
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
        $warehouse["pic"] = Storage::url($warehouse["pic"]);
        return formatResponse(false,(["warehouse"=>$warehouse]));
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
        
        $path = $this->warehouseService->handleUpdateImage($request->file("pic"),$this->path,$this->warehouse->find($id)->name,$this->warehouse->find($id)->pic,$request["name"]);
        is_null($path) ? "" : $data["pic"]=$path;

        $warehouse = $this->warehouse->find($id)->update($data);
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
        deleteImage($this->warehouse->find($id)->pic);

        $this->warehouse->find($id)->delete();
        return formatResponse(false,(["warehouse"=>["warehouse deleted successfully"]]));
    }
}
