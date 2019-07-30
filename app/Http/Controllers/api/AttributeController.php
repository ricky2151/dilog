<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StoreAttribute;
use App\Http\Requests\UpdateAttribute;
use App\Models\Attribute;
use App\Models\Goods;
use App\Services\AttributeService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    private $attributeService, $attribute;

    public function __construct(AttributeService $attributeService, Attribute $attribute)
    {
        $this->attributeService = $attributeService;
        $this->attribute = $attribute;
    }
    /**
     * Display a listing of the attribute.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->attributeService->handleEmptyModel();
        return formatResponse(false,(["attributes"=>$this->attribute->latest()->get()]));
    }

    /**
     * Store a newly created attribute in storage.
     *
     * @param  \Illuminate\Http\StoreAttribute  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAttribute $request)
    {
        $data = $request->validated();
        $this->attribute->create($data);

        return formatResponse(false,(["attribute"=>["Attribute successfully created"]]));
    }

    /**
     * Display the specified attribute.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->attributeService->handleInvalidParameter($id);
        $this->attributeService->handleModelNotFound($id);

        return formatResponse(false,(["attribute"=>$this->attribute->find($id)]));
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->attributeService->handleInvalidParameter($id);
        $this->attributeService->handleModelNotFound($id);

        return formatResponse(false,(["attribute"=>$this->attribute->find($id), "master_data"=>[]]));
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  \Illuminate\Http\UpdateAttribute  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAttribute $request, $id)
    {
        $this->attributeService->handleInvalidParameter($id);
        $this->attributeService->handleModelNotFound($id);

        $attribute = $this->attribute->find($id)->update($request->validated());
        return formatResponse(false,(["attribute"=>["attribute was successfully updated"]]));
    }

    /**
     * Remove the specified attribute from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->attributeService->handleInvalidParameter($id);
        $this->attributeService->handleModelNotFound($id);
        
        DB::beginTransaction();
        try {
            $this->attribute->find($id)->goods()->sync([]);
            $this->attribute->find($id)->delete();
            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Attribute");
        }
        
        return formatResponse(false,(["attribute"=>["attribute deleted successfully"]]));
    }
}
