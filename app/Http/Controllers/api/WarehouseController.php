<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UpdateWarehouse;
use App\Http\Requests\StoreWarehouse;
use App\Http\Requests\StoreStockOpname;
use App\Http\Requests\StoreStockOpnameDetail;
use App\Http\Requests\UpdateStockOpnameDetail;
use App\Services\WarehouseService;
use App\Exceptions\DatabaseTransactionErrorException;
use App\Models\Warehouse;
use App\Models\StockOpname;
use App\Models\Goods;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Image;
use Storage;
use File;
use App\Http\Controllers\Controller;

class WarehouseController extends Controller
{
    private $warehouseService, $warehouse, $path, $stockOpname, $goods;

    public function __construct(WarehouseService $warehouseService, Warehouse $warehouse, StockOpname $stockOpname, Goods $goods)
    {
        $this->warehouseService = $warehouseService;
        $this->warehouse = $warehouse;
        $this->user = auth('api')->user();
        $this->path = 'warehouse';
        $this->stockOpname = $stockOpname;
        $this->goods = $goods;
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
     * Display a listing of the stockOpname in specific warehouse.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showStockOpnames($id)
    {
        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);

        $warehouse = $this->warehouse->find($id)->stockOpnames()->latest()->get();

        return formatResponse(false,(["warehouse"=>$warehouse]));
    }

    /**
     * Store stockOpname in specific warehouse.
     *
     * @param  \Illuminate\Http\StoreStockOpname  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeStockOpnames(StoreStockOpname $request, $id)
    {
        $data = $request->validated();

        $this->warehouseService->handleInvalidParameter($id);
        $this->warehouseService->handleModelNotFound($id);
        
        $data = Arr::add($data, 'user_id', $this->user->makeVisible('id')['id']);  
        $warehouse = $this->user->warehouse->find($id)->stockOpnames()->create($data);

        return formatResponse(false,(["warehouse"=>["Stock opname in specific warehouse successfully created"]]));
    }

    /**
     * Create stockOpnameDetail in specific stockOpname.
     *
     * @param  $id stockName id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createStockOpnamesDetails($warehouseId)
    {
        $this->warehouseService->handleInvalidParameter($warehouseId);
        $this->warehouseService->handleModelNotFound($warehouseId);

        return formatResponse(false,(["warehouse"=>$this->warehouse->find($warehouseId)->getGoodsWithStock()]));
    }

    /**
     * Store detail stock opname in specific warehouse.
     *
     * @param  \Illuminate\Http\StoreStockOpnameDetail  $request
     * @param  $stockOpnamesId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeStockOpnamesDetails(StoreStockOpnameDetail $request, $stockOpnamesId)
    {
        $data = $request->validated();

        $this->warehouseService->handleInvalidParameter($stockOpnamesId);
        $this->warehouseService->handleModelStockOpnameNotFound($stockOpnamesId);
        $result = $this->warehouseService->storeStockOpnamesDetails($data, $this->stockOpname->find($stockOpnamesId)->warehouse->getGoodsWithStock(), $stockOpnamesId);

        return formatResponse(false,(["warehouse"=>[$result]]));
    }

    /**
     * Show the form for editing the Stock Opnames Detail in specific Stock Opname.
     *
     * @param  $stockOpnamesId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editStockOpnamesDetails($stockOpnamesId)
    {
        $this->warehouseService->handleInvalidParameter($stockOpnamesId);
        $this->warehouseService->handleModelStockOpnameNotFound($stockOpnamesId);
        $stockOpnameDetails = $this->warehouseService->detailStockOpnames($stockOpnamesId);

        return formatResponse(false,(["warehouse"=>$stockOpnameDetails]));
    }

    /**
     * Update the specified Warehouse in storage.
     *
     * @param  \Illuminate\Http\UpdateStockOpnameDetail  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function updateStockOpnamesDetails(UpdateStockOpnameDetail $request, $stockOpnamesId)
    {
        $data = $request->validated();

        $this->warehouseService->handleInvalidParameter($stockOpnamesId);
        $this->warehouseService->handleModelStockOpnameNotFound($stockOpnamesId);
        return $this->warehouseService->updateStockOpnamesDetails($data, $this->stockOpname->find($stockOpnamesId)->stockOpnameDetails, $stockOpnamesId);

        return formatResponse(false,(["warehouse"=>["details stock opname in specific warehouse successfully updated"]]));
    }
    

    /**
     * update specific Stock Opname status into waiting.
     *
     * @param  $id stockName id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setWaitings($stockOpnamesId)
    {
        $this->warehouseService->handleInvalidParameter($stockOpnamesId);
        $this->warehouseService->handleModelStockOpnameNotFound($stockOpnamesId);
        
        $this->stockOpname->find($stockOpnamesId)->setWaitings();

        return formatResponse(false,(["warehouse"=>["stockopname status change to waiting"]]));
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
