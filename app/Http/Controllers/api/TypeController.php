<?php

namespace App\Http\Controllers\api;

use App\Services\TypeService;
use App\Http\Requests\StoreType;
use App\Http\Requests\UpdateType;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    private $typeService,$type;

    public function __construct(TypeService $typeService, Type $type)
    {
        $this->typeService = $typeService;
        $this->type = $type;
    }
    /**
     * Display a listing of the type.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->typeService->handleEmptyModel();

        $types = $this->type->latest()->get();
        return formatResponse(false,(["types"=>$types]));
    }

    /**
     * Store a newly created type in storage.
     *
     * @param  \Illuminate\Http\StoreType  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreType $request)
    {
        $data = $request->validated();

        $this->type->create($data);
        return formatResponse(false,(["type"=>["type successfully created"]]));
    }

    /**
     * Display the specified type.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->typeService->handleInvalidParameter($id);
        $this->typeService->handleModelNotFound($id);

        return formatResponse(false,(["type"=>$this->type->find($id)]));
    }

    /**
     * Show the form for editing the specified type.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->typeService->handleInvalidParameter($id);
        $this->typeService->handleModelNotFound($id);

        return formatResponse(false,(["type"=>$this->type->find($id,['id','name']),'master_data'=>[]]));
    }

    /**
     * Update the specified type in storage.
     *
     * @param  \Illuminate\Http\UpdateType  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateType $request, $id)
    {
        $this->typeService->handleInvalidParameter($id);
        $this->typeService->handleModelNotFound($id);

        $this->type->find($id)->update($request->validated());
        return formatResponse(false,(["type"=>["type was successfully updated"]]));
    }

    /**
     * Remove the specified type from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->typeService->handleInvalidParameter($id);
        $this->typeService->handleModelNotFound($id);

        $this->type->find($id)->delete();
        return formatResponse(false,(["type"=>["type deleted successfully"]]));
    }
}
