<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StoreCategory;
use App\Http\Requests\UpdateCategory;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DatabaseTransactionErrorException;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    private $categoryService, $category;

    public function __construct(CategoryService $categoryService, Category $category)
    {
        $this->categoryService = $categoryService;
        $this->category = $category;
    }

    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->categoryService->handleEmptyModel();
        $categories = $this->category->latest()->get();
        return formatResponse(false,(["categories"=>$categories]));
    }

    /**
     * Display a listing of the goods with stock in this category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function goodsCategory($id)
    {
        $this->categoryService->handleEmptyModel();
        $goods = $this->category->find($id)->goodsStock();

        return formatResponse(false,(["categories"=>$goods]));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\StoreCategory  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategory $request)
    {
        $data = $request->validated();
        $this->category->create($data);

        return formatResponse(false,(["category"=>["Category successfully created"]]));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->categoryService->handleInvalidParameter($id);
        $this->categoryService->handleModelNotFound($id);

        $category = $this->category->find($id);
        return formatResponse(false,(["category"=>$category]));
    }

    /**
     *  Show the form for editing the specified category.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->categoryService->handleInvalidParameter($id);
        $this->categoryService->handleModelNotFound($id);

        $category = $this->category->find($id);
        return formatResponse(false,(["category"=>$category]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateCategory  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategory $request, $id)
    {
        $this->categoryService->handleInvalidParameter($id);
        $this->categoryService->handleModelNotFound($id);

        $category = $this->category->find($id)->update($request->validated());
        return formatResponse(false,(["category"=>["category was successfully updated"]]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->categoryService->handleInvalidParameter($id);
        $this->categoryService->handleModelNotFound($id);

        DB::beginTransaction();
        try {
            $this->category->find($id)->goods()->sync([]);
            $this->category->find($id)->delete();

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Category");
        }

        
        
        return formatResponse(false,(["category"=>["category deleted successfully"]]));
    }
}
