<?php

namespace App\Http\Controllers\api;

use App\Services\GoodsRackService;
use App\Http\Requests\StoreGoodsRack;
use App\Http\Requests\UpdateGoodsRack;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DatabaseTransactionErrorException;
use App\Models\GoodsRack;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsRackController extends Controller
{
    private $goodsRackService,$goodsRack;

    public function __construct(GoodsRackService $goodsRackService, GoodsRack $goodsRack)
    {
        $this->goodsRackService = $goodsRackService;
        $this->goodsRack = $goodsRack;
    }

    /**
     * Display a listing of the Goods Rack.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->goodsRackService->handleEmptyModel();
        $goodsRacks = $this->goodsRack->latest()->get();

        $goodsRacks = $goodsRacks->map(function ($goodsRack) {
            
            $goodsRack = Arr::add($goodsRack, 'goods_name', $goodsRack['goods']['name']);
            $goodsRack = Arr::add($goodsRack, 'rack_name', $goodsRack['rack']['name']);

            return Arr::except($goodsRack, ['goods','rack']);
        });

        return formatResponse(false,(["goods_rack"=>$goodsRacks]));
    }

    /**
     * Show the form for creating a new Goods Rack.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        // $this->goodsRackService->handleGetAllDataForGoodsCreation();
        return formatResponse(false,($this->goodsRack->allDataCreate()));
    }

    /**
     * Store a newly created Goods Rack in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreGoodsRack $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {

            $goodsRack = $this->goodsRack->create($data);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("GoodsRack");
        }
        return formatResponse(false,(["goods_rack"=>["goods rack successfully created"]]));
    }

    /**
     * Display the specified Goods Rack.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->goodsRackService->handleInvalidParameter($id);
        $this->goodsRackService->handleModelNotFound($id);

        $goodsRack = $this->goodsRack->find($id);
        $goodsRack = collect($goodsRack)->put("rack_name",$goodsRack->rack->name)->put("goods_name",$goodsRack->goods->name);

        return formatResponse(false,(["goods_rack"=>$goodsRack]));
    }

    /**
     * Show the form for editing the specified Goods Rack.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->goodsRackService->handleInvalidParameter($id);
        $this->goodsRackService->handleModelNotFound($id);
        // $this->goodsRackService->handleGetAllDataForGoodsCreation();

        $allMaterial = collect($this->goodsRack->allDataCreate());
        $goodsRack = collect($this->goodsRack->find($id));

        // $concatenated = $goodsRack->union($allMaterial)->union($this->showFormatData($id));
        $concatenated = $goodsRack->union($allMaterial);

        return formatResponse(false,(["goods_rack"=>$concatenated]));
    }

    /**
     * Update the specified Goods Rack in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateGoodsRack $request, $id)
    {
        $data = $request->validated();
        $this->goodsRackService->handleInvalidParameter($id);
        $this->goodsRackService->handleModelNotFound($id);

        $goodsRack = $this->goodsRack->find($id);

        DB::beginTransaction();
        try {
            // $batchs = collect(Arr::pull($data,'batch'))->unique(function ($item) {
            //     return $item['batch_number'];
            // });

            $goodsRack->update($data);
            // $goodsRack->sources()->sync($batchs);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("GoodsRack");
        }
        return formatResponse(false,(["goods_rack"=>["Goods Rack successfully updated"]]));
    }

    /**
     * Remove the specified Goods Rack from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->goodsRackService->handleInvalidParameter($id);
        $this->goodsRackService->handleModelNotFound($id);

        DB::beginTransaction();
        try {
            // $this->goodsRack->find($id)->sources()->sync([]);
            $this->goodsRack->find($id)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("GoodsRack");
        }
        return formatResponse(false,(["goods_rack"=>["Goods Rack deleted successfully"]]));
    }
    
    /**
     * Format goods relation data in method show.
     *
     * @param  $id
     * @return Illuminate\Support\Collection
     */
    public function showFormatData($id){
        $goodsRackBatchs = $this->goodsRack->find($id)->sources;

        $goodsRackBatchs = $goodsRackBatchs->map(function ($item) {
            return ['goods_rack_id' => $item['pivot']['goods_rack_id'], 'source_id' => $item['pivot']['source_id'],'stock' => $item['pivot']['stock'], 'batch_number' => $item['pivot']['batch_number']];
        });

        $data = collect(["goods_rack_batchs"=>$goodsRackBatchs]);

        return $data;
    }
}
