<?php

namespace App\Http\Controllers\api;

use App\Services\CategoryPriceSellingService;
use App\Http\Requests\StoreCategoryPriceSelling;
use App\Http\Requests\UpdateCategoryPriceSelling;
use App\Models\CategoryPriceSelling;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryPriceSellingController extends Controller
{
    private $categoryPriceSellingService, $categoryPriceSelling;

    public function __construct(CategoryPriceSellingService $categoryPriceSellingService, CategoryPriceSelling $categoryPriceSelling)
    {
        $this->categoryPriceSellingService = $categoryPriceSellingService;
        $this->categoryPriceSelling = $categoryPriceSelling;
    }
    /**
     * Display a listing of the Category Price Selling.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->categoryPriceSellingService->handleEmptyModel();

        return formatResponse(false,(["category_price_sellings"=>$this->categoryPriceSelling->latest()->get()]));
    }

    /**
     * Store a newly created Category Price Selling in storage.
     *
     * @param  \Illuminate\Http\StoreCategoryPriceSelling  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryPriceSelling $request)
    {
        $data = $request->validated();

        $this->categoryPriceSelling->create($data);
        return formatResponse(false,(["category_price_selling"=>["Category Price Selling successfully created"]]));
    }

    /**
     * Display the specified Category Price Selling.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->categoryPriceSellingService->handleInvalidParameter($id);
        $this->categoryPriceSellingService->handleModelNotFound($id);

        return formatResponse(false,(["category_price_selling"=>$this->categoryPriceSelling->find($id)]));
    }

    /**
     * Show the form for editing the specified type.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->categoryPriceSellingService->handleInvalidParameter($id);
        $this->categoryPriceSellingService->handleModelNotFound($id);

        return formatResponse(false,(["category_price_selling"=>$this->categoryPriceSelling->find($id,['id','name','discount']),'master_data'=>[]]));
    }

    /**
     * Update the specified Category Price Selling in storage.
     *
     * @param  \Illuminate\Http\UpdateCategoryPriceSelling  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryPriceSelling $request, $id)
    {
        $this->categoryPriceSellingService->handleInvalidParameter($id);
        $this->categoryPriceSellingService->handleModelNotFound($id);

        $this->categoryPriceSelling->find($id)->update($request->validated());
        return formatResponse(false,(["category_price_selling"=>["Category Price Selling was successfully updated"]]));
    }

    /**
     * Remove the specified Category Price Selling from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->categoryPriceSellingService->handleInvalidParameter($id);
        $this->categoryPriceSellingService->handleModelNotFound($id);

        $this->categoryPriceSelling->find($id)->delete();
        return formatResponse(false,(["category_price_selling"=>["Category Price Selling deleted successfully"]]));
    }
}
