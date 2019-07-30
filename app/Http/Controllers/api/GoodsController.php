<?php

namespace App\Http\Controllers\api;

use App\Services\GoodsService;
use App\Http\Requests\StoreGoods;
use App\Http\Requests\UpdateGoods;
use Illuminate\Database\QueryException;
use App\Models\Goods;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Storage;

class GoodsController extends Controller
{
    private $goodsService,$goods,$path,$user;

    public function __construct(GoodsService $goodsService, Goods $goods)
    {
        $this->goodsService = $goodsService;
        $this->goods = $goods;
        $this->path = 'goods';
        $this->user = auth('api')->user();
    }

    /**
     * Display a listing of the goods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->goodsService->handleEmptyModel();

        return formatResponse(false,(["goods"=>$this->goods->index()]));
    }

    /**
     * Display a listing of the rack in specific goods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function racks($id)
    {
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);

        return formatResponse(false,(["racks"=>$this->goods->find($id)->getRack()]));
    }

    /**
     * Display a listing of the selling price in specific goods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sellingPrices($id)
    {
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);

        return formatResponse(false,(["price_sellings"=>$this->goods->find($id)->getSellingPrices()]));
    }

    /**
     * Display a listing of the pricelist in specific goods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pricelists($id)
    {
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);

        $CollectionGoods = $this->goods->find($id)->getPricelist();

        return formatResponse(false,(["pricelists"=>$CollectionGoods]));
    }

    /**
     * Show the form for creating a new goods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return formatResponse(false,($this->goods->getMasterData()));
    }

    /**
     * Store a newly created goods in storage.
     *
     * @param  \Illuminate\Http\StoreGoods  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreGoods $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $name =  $data["name"].Str::random(10);
            $path = $this->goodsService->handleUploadImage($request->file("thumbnail"),$this->path,$name);
            $data["thumbnail"] = $path;

            $attribute_goods = collect(Arr::pull($data,'attribute_goods'))->unique(function ($item) {
                return $item['attribute_id'].$item['value'];
            });

            $category_goods = collect(Arr::pull($data,'category_goods'))->unique(function ($item) {
                return $item['category_id'];
            });

            $pricelists = collect(Arr::pull($data,'pricelists'))->toArray();

            $priceSellings = collect(Arr::pull($data,'price_sellings'))->unique(function ($item) {
                return $item['warehouse_id'];
            })->toArray();
            
            $material_goods = collect(Arr::pull($data,'material_goods'))->unique(function ($item) {
                return $item['name'];
            })->toArray();

            $goods = $this->user->goods()->create($data);
            $goods->attributes()->attach($attribute_goods);
            $goods->categories()->attach($category_goods);
            $goods->materials()->createMany($material_goods);
            $goods->priceSellings()->createMany($priceSellings);
            $goods->pricelists()->createMany($pricelists);

            DB::commit();
        } catch (\Throwable $e) {
            deleteImage($data["thumbnail"]);
            DB::rollback();
            throw new DatabaseTransactionErrorException("Goods");
        }
        return formatResponse(false,(["goods"=>["goods successfully created"]]));
    }

    /**
     * Display the specified goods.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);

        return formatResponse(false,(["goods"=>$this->goods->find($id)]));
    }

    /**
     * Show the form for editing the specified goods.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);
        // $this->goodsService->handleGetAllDataForGoodsCreation();
        
        return formatResponse(false,(["goods"=>$this->goods->getDataAndRelation($id), "master_data"=>$this->goods->getMasterData()]));
    }

    /**
     * Update the specified goods in storage.
     *
     * @param  \Illuminate\Http\UpdateGoods  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateGoods $request, $id)
    {
        $data = $request->validated();
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);

        $goods = $this->goods->find($id);

        DB::beginTransaction();
        try {
            $oldThumnail = $goods->find($id)->thumbnail;
            $path = $this->goodsService->handleUpdateImageGetPath($request->file("thumbnail"),($goods->find($id)->name.Str::random(10)),$request["name"],$data["is_image_delete"]);
            is_null($path) ? $path = "" : $data["thumbnail"]=$this->path."/".$path;
            $data["user_id"] = $this->user["id"];
            
            $attribute_goods = collect(Arr::pull($data,'attribute_goods'))->unique(function ($item) {
                return $item['attribute_id'].$item['value'];
            });

            $category_goods = collect(Arr::pull($data,'category_goods'))->unique(function ($item) {
                return $item['category_id'];
            });

            $pricelists = collect(Arr::pull($data,'pricelists'))->toArray();

            $priceSellings = collect(Arr::pull($data,'price_sellings'))->unique(function ($item) {
                return $item['warehouse_id'];
            })->toArray();
            
            $material_goods = collect(Arr::pull($data,'material_goods'))->unique(function ($item) {
                return $item['name'];
            })->toArray();

            // return $material_goods;
            
            $goods->update($data);
            $goods->attributes()->sync($attribute_goods);
            $goods->categories()->sync($category_goods);
            is_null($priceSellings) ? "" : $goods->updatePriceSellings($priceSellings);
            is_null($material_goods) ? "" : $goods->updateMaterials($material_goods);
            is_null($pricelists) ? "" : $goods->updatePricelists($pricelists);

            $this->goodsService->handleUpdateImage($request->file("thumbnail"),$oldThumnail, $path, $this->path,$data["is_image_delete"]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Goods");
        }

        return formatResponse(false,(["goods"=>["goods successfully updated"]]));
    }

    /**
     * Remove the specified goods from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);

        $thumbnail = $this->goods->thumbnail;
        DB::beginTransaction();
        try {
            $this->attributes()->sync([]);
            $this->categories()->sync([]);
            $this->pricelists()->delete();
            $this->materials()->delete();
            $this->goodsRack()->delete();
            $this->priceSellings()->delete();

            $this->delete();
            deleteImage($thumbnail);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Goods");
        }

        return formatResponse(false,(["goods"=>["goods deleted successfully"]]));
    }
}
