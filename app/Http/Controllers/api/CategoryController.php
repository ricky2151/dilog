<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StoreCategory;
use App\Http\Requests\UpdateCategory;
use App\Services\CategoryService;
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
        $categories = $this->category->all();
        return formatResponse(false,(["categories"=>$categories]));
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

        $this->category->find($id)->delete();
        return formatResponse(false,(["category"=>["category deleted successfully"]]));
    }
}
