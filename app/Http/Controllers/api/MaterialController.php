<?php

namespace App\Http\Controllers\api;

use App\Services\MaterialService;
use App\Http\Requests\StoreMaterial;
use App\Http\Requests\UpdateMaterial;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    private $materialService,$material;

    public function __construct(MaterialService $materialService, Material $material)
    {
        $this->materialService = $materialService;
        $this->material = $material;
    }
    /**
     * Display a listing of the material.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->materialService->handleEmptyModel();

        return formatResponse(false,(["materials"=>$this->material->latest()->get()]));
    }

    /**
     * Store a newly created material in storage.
     *
     * @param  \Illuminate\Http\StoreMaterial  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMaterial $request)
    {
        $data = $request->validated();

        $this->material->create($data);
        return formatResponse(false,(["material"=>["material successfully created"]]));
    }

    /**
     * Display the specified material.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->materialService->handleInvalidParameter($id);
        $this->materialService->handleModelNotFound($id);

        return formatResponse(false,(["material"=>$this->material->find($id)]));
    }

    /**
     * Show the form for editing the specified material.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->materialService->handleInvalidParameter($id);
        $this->materialService->handleModelNotFound($id);

        return formatResponse(false,(["material"=>$this->material->getDataAndRelation($id), "master_data"=>[]]));
    }

    /**
     * Update the specified material in storage.
     *
     * @param  \Illuminate\Http\UpdateMaterial  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMaterial $request, $id)
    {
        $this->materialService->handleInvalidParameter($id);
        $this->materialService->handleModelNotFound($id);

        $this->material->find($id)->update($request->validated());
        return formatResponse(false,(["material"=>["material was successfully updated"]]));
    }

    /**
     * Remove the specified material from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->materialService->handleInvalidParameter($id);
        $this->materialService->handleModelNotFound($id);

        $this->material->find($id)->delete();
        return formatResponse(false,(["material"=>["material deleted successfully"]]));
    }
}
