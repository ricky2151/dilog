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
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->categoryService->handleEmptyModel();
        $categories = Category::all();
        return $this->formatResponse(false,(["categories"=>$categories]));
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
        $category = new Category;
        $category = $category->create($data);

        return $this->formatResponse(false,(["category"=>["Category successfully created"]]));
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

        $category = Category::find($id);
        return $this->formatResponse(false,(["category"=>$category]));
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

        $category = Category::find($id);
        $category->update($request->validated());
        return $this->formatResponse(false,(["category"=>["category was successfully updated"]]));
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
        
        $category = Category::find($id);
        $category->delete();
        return $this->formatResponse(false,(["category"=>["category deleted successfully"]]));
    }


    /**
     * Format Response User Controller
     *
     * @param  bool  $error
     * @param  array $array
     * @return \Illuminate\Http\JsonResponse
     */
    public function formatResponse(bool $error, array $array){
        return response()->json([
            "error" => $error,
            ($error == false ? "data" : "message") => $array
        ]);
    }
}
