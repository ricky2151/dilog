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
        $CollectionGoods = $this->goods->latest()->get();

        return formatResponse(false,(["goods"=>$CollectionGoods]));
    }

    /**
     * Show the form for creating a new goods.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $this->goodsService->handleGetAllDataForGoodsCreation();
        $data = $this->goods->allDataCreate();
        return formatResponse(false,([$data]));
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

            $attribute_goods = Arr::pull($data,'attribute_goods');
            $category_goods = Arr::pull($data,'category_goods');
            $material_goods = Arr::pull($data,'material_goods');

            $goods = $this->user->goods()->create($data);
            $goods->attributes()->attach($attribute_goods);
            $goods->categories()->attach($category_goods);
            $goods->materials()->createMany($material_goods);

            DB::commit();
        } catch (\Throwable $e) {
            deleteImage($data["thumbnail"]);
            DB::rollback();
            throw new DatabaseTransactionErrorException("Goods");
        }
        return formatResponse(false,(["goods"=>["goods successfully created"]]));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->goodsService->handleInvalidParameter($id);
        $this->goodsService->handleModelNotFound($id);
        $this->goodsService->handleGetAllDataForGoodsCreation();

        $allMaterial = collect($this->goods->allDataCreate());

        $goods = $this->goods->find($id);
        $goods["thumbnail"] = Storage::url($goods["pic"]);
        $goods = collect($goods);
        
        $concatenated = $goods->union($allMaterial)->union($this->showFormatData($id));

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

        $material_goods_delete = Arr::pull($data,'material_goods_delete');
        $material_goods_update = Arr::pull($data,'material_goods_update');

        $this->goodsService->checkRelationship($id,collect($material_goods_delete)->pluck("id"));
        $this->goodsService->checkRelationship($id,collect($material_goods_update)->pluck("id"));

        $goods = $this->goods->find($id);

        DB::beginTransaction();
        try {
            $oldThumnail = $goods->find($id)->thumbnail;
            $path = $this->goodsService->handleUpdateImageGetPath($request->file("thumbnail"),($goods->find($id)->name.Str::random(10)),$request["name"]);
            is_null($path) ? "" : $data["thumbnail"]=$this->path."/".$path;
            $data["user_id"] = $this->user["id"];
            
            $attribute_goods = Arr::pull($data,'attribute_goods');
            $category_goods = Arr::pull($data,'category_goods');
            $material_goods_new = Arr::pull($data,'material_goods_new');
            
            $goods->update($data);
            $goods->attributes()->sync($attribute_goods);
            $goods->categories()->sync($category_goods);
            is_null($material_goods_new) ? "" : $goods->materials()->createMany($material_goods_new);
            $goods->updateManyAtribut($material_goods_update);
            $goods->deleteManyAtribut($material_goods_delete);

            $this->goodsService->handleUpdateImage($request->file("thumbnail"),$oldThumnail, $path, $this->path);

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
        deleteImage($this->goods->find($id)->thumbnail);

        $this->goods->find($id)->attributes()->sync([]);
        $this->goods->find($id)->categories()->sync([]);
        $this->goods->find($id)->materials()->delete();
        $this->goods->find($id)->delete();

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

        $goodsAttributes = $goodsAttributes->map(function ($item) {
            return ['id' => $item['id'], 'name' => $item['name'],'value'=> $item['pivot']['value']];
        });

        $goodsCategories = $goodsCategories->map(function ($item) {
            return ['id' => $item['id'], 'name' => $item['name']];
        });

        $data = collect(["attribute_goods" => $goodsAttributes, "category_goods"=>$goodsCategories , "material_goods" => $goodsMaterials]);

        return $data;
    }
}
