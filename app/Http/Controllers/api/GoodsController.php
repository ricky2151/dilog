<?php

namespace App\Http\Controllers\api;

use App\Services\GoodsService;
use App\Http\Requests\StoreGoods;
use App\Http\Requests\UpdateGoods;
use App\Models\Goods;
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
            $goods->attributes()->sync($attribute_goods);
            $goods->categories()->attach($category_goods);
            $goods->materials()->createMany($material_goods);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            deleteImage($data["thumbnail"]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goods $goods)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goods $goods)
    {
        //
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
