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
        $CollectionGoods = $this->goods->index();

        return formatResponse(false,(["goods"=>$CollectionGoods]));
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

        $CollectionGoods = $this->goods->find($id)->getRack();

        return formatResponse(false,(["goods"=>$CollectionGoods]));
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

        $CollectionGoods = $this->goods->find($id)->getSellingPrices();

        return formatResponse(false,(["goods"=>$CollectionGoods]));
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

        return formatResponse(false,(["goods"=>$CollectionGoods]));
    }

    /**
     * Show the form for creating a new goods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        // $this->goodsService->handleGetAllDataForGoodsCreation();
        $data = $this->goods->allDataCreate();
        return formatResponse(false,($data));
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

            $pricelists = collect(Arr::pull($data,'pricelists'))->unique(function ($item) {
                return $item['supplier_id'].$item['price'];
            });

            $priceSellings = collect(Arr::pull($data,'price_sellings'))->unique(function ($item) {
                return $item['warehouse_id'];
            })->toArray();
            
            $material_goods = collect(Arr::pull($data,'material_goods'))->unique(function ($item) {
                return $item['name'];
            })->toArray();

            $goods = $this->user->goods()->create($data);
            $goods->attributes()->attach($attribute_goods);
            $goods->categories()->attach($category_goods);
            $goods->suppliers()->attach($pricelists);
            $goods->materials()->createMany($material_goods);
            $goods->priceSelling()->createMany($priceSellings);

            DB::commit();
        } catch (\Throwable $e) {
            deleteImage($data["thumbnail"]);
            DB::rollback();
            return $e;
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

        $goods = $this->goods->find($id);

        return formatResponse(false,(["goods"=>$goods]));
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

        $allMaterial = collect($this->goods->allDataCreate());

        $goods = $this->goods->find($id);
        $goods["thumbnail"] = Storage::url($goods["thumbnail"]);
        $goods = collect($goods);
        
        $concatenated = $goods->union($allMaterial)->union($this->showFormatData($id));

        // return  $this->goods->find($id)->suppliers;
        return formatResponse(false,(["goods"=>$concatenated]));
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

            $pricelists = collect(Arr::pull($data,'pricelists'))->unique(function ($item) {
                return $item['supplier_id'].$item['price'];
            });

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
            $goods->suppliers()->sync($pricelists);
            is_null($priceSellings) ? "" : $goods->updatePriceSellings($priceSellings);
            is_null($material_goods) ? "" : $goods->updateMaterials($material_goods);

            $this->goodsService->handleUpdateImage($request->file("thumbnail"),$oldThumnail, $path, $this->path,$data["is_image_delete"]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
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
        $thumbnail = $this->goods->find($id)->thumbnail;
        DB::beginTransaction();
        try {
            $this->goods->find($id)->attributes()->sync([]);
            $this->goods->find($id)->categories()->sync([]);
            $this->goods->find($id)->suppliers()->sync([]);
            $this->goods->find($id)->materials()->delete();
            $this->goods->find($id)->goodsRack()->delete();

            $this->goods->find($id)->delete();
            deleteImage($thumbnail);

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("Goods");
        }
        

        return formatResponse(false,(["goods"=>["goods deleted successfully"]]));
    }

    /**
     * Format goods relation data in method show.
     *
     * @param  $id
     * @return Illuminate\Support\Collection
     */
    public function showFormatData($id){
        $goodsAttributes = $this->goods->find($id)->attributes;
        $goodsCategories = $this->goods->find($id)->categories;
        $goodsMaterials = $this->goods->find($id)->materials;
        $pricelists = $this->goods->find($id)->suppliers;
        $priceSellings = $this->goods->find($id)->getSellingPrices();

        // return $pricelists;

        $goodsAttributes = $goodsAttributes->map(function ($item) {
            return ['id' => $item['id'], 'name' => $item['name'],'value'=> $item['pivot']['value']];
        });

        $goodsCategories = $goodsCategories->map(function ($item) {
            return ['id' => $item['id'], 'name' => $item['name']];
        });

        $pricelists = $pricelists->map(function ($item) {
            $item = Arr::add($item, 'price', $item['pivot']['price']);
            return Arr::except($item, ['pivot']);
        });


        $data = collect(["price_sellings"=>$priceSellings,"pricelists" => $pricelists,"attribute_goods" => $goodsAttributes, "category_goods"=>$goodsCategories , "material_goods" => $goodsMaterials]);

        return $data;
    }
}
