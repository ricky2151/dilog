<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StoreRack;
use App\Http\Requests\UpdateRack;
use App\Services\RackService;
use App\Models\Rack;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RackController extends Controller
{
    private $rackService,$rack;

    public function __construct(RackService $rackService, Rack $rack)
    {
        $this->rackService = $rackService;
        $this->rack = $rack;
    }
    /**
     * Display a listing of the rack.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->rackService->handleEmptyModel();
        $racks = $this->rack->latest()->get();

        $racks = $racks->map(function ($rack) { 
            $rack = Arr::add($rack, 'warehouse_name', $rack['warehouse']['name']);
            return Arr::except($rack, ['warehouse']);
        });

        return formatResponse(false,(["racks"=>$racks]));
    }

    /**
     * Show the form for creating a new Rack.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $this->rackService->handleGetAllDataForGoodsCreation();
        return formatResponse(false,($this->rack->getMasterData()));
    }

    /**
     * Store a newly created rack in storage.
     *
     * @param  \Illuminate\Http\StoreRack  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRack $request)
    {
        $data = $request->validated();

        $goodsRacks = collect(Arr::pull($data,'goods_racks'))->unique(function ($item) {
            return $item['goods_id'].$item['stock'];
        })->toArray();

        DB::beginTransaction();
        try {
            $rack = $this->rack->create($data);
            $rack->goodsRacks()->createMany($goodsRacks);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Rack");
        }

        return formatResponse(false,(["rack"=>["rack successfully created"]]));
    }

    /**
     * Display the specified rack.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);

        $rack = $this->rack->find($id);
        $rack = collect($rack)->put('warehouse_name',$rack->warehouse->name);
        
        return formatResponse(false,(["rack"=>$rack]));
    }

    /**
     * Show the form for editing the specified rack.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);
        $this->rackService->handleGetAllDataForGoodsCreation();

        return formatResponse(false,(["rack"=>$this->rack->getDataAndRelation($id), "master_data"=>$this->rack->getMasterData()]));
    }

    /**
     * Update the specified rack in storage.
     *
     * @param  \Illuminate\Http\UpdateRack  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRack $request, $id)
    {
        $data = $request->validated();
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);

        $rack = $this->rack->find($id);
        
        DB::beginTransaction();
        try {
            $goodsRacks = Arr::pull($data,'goods_racks');
            
            $rack->update($data);
            is_null($goodsRacks) ? "" : $rack->updateGoodsRack($goodsRacks);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("Rack");
        }

        return formatResponse(false,(["rack"=>["rack was successfully updated"]]));
    }

    /**
     * Remove the specified rack from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);
        DB::beginTransaction();
        try {
            $this->rack->find($id)->goodsRacks()->delete();
            $this->rack->find($id)->delete();

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Rack");
        }
        
        return formatResponse(false,(["rack"=>["rack deleted successfully"]]));
    }

    /**
     * get GoodRacks in spedcific racks
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function goodsRacks($id)
    {
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);

        $data = $this->rack->find($id)->goodsRacks->map(function($item){
            return ['id'=>$item['id'], 
                'rack_id'=>$item['rack_id'], 
                'rack_name'=>$item['rack']['name'],
                'goods_id'=>$item['goods_id'], 
                'goods_name'=>$item['goods']['name'],  
                'stock'=>$item['stock']];
        });

        return formatResponse(false,(["goods_racks"=>$data]));
    }
}
