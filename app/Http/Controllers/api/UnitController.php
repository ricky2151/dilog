<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StoreUnit;
use App\Http\Requests\UpdateUnit;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Services\UnitService;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
    private $unitService,$unit;

    public function __construct(UnitService $unitService, Unit $unit)
    {
        $this->unitService = $unitService;
        $this->unit = $unit;
    }

    /**
     * Display a listing of the unit.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->unitService->handleEmptyModel();
        return formatResponse(false,(["units"=>$this->unit->latest()->get()]));
    }

    /**
     * Store a newly created unit in storage.
     *
     * @param  \Illuminate\Http\StoreUnit  $StoreUnit
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUnit $request)
    {
        $data = $request->validated();

        $this->unit->create($data);
        return formatResponse(false,(["unit"=>["unit successfully created"]]));
    }

    /**
     * Display the specified unit.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->unitService->handleInvalidParameter($id);
        $this->unitService->handleModelNotFound($id);

        $unit = $this->unit->find($id);
        return formatResponse(false,(["unit"=>$unit]));
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->unitService->handleInvalidParameter($id);
        $this->unitService->handleModelNotFound($id);

        return formatResponse(false,(["unit"=>$this->unit->find($id,['id','name']),'master_data'=>[]]));
    }

    /**
     * Update the specified unit in storage.
     *
     * @param  \Illuminate\Http\UpdateUnit  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUnit $request, $id)
    {
        $this->unitService->handleInvalidParameter($id);
        $this->unitService->handleModelNotFound($id);

        $this->unit->find($id)->update($request->validated());
        return formatResponse(false,(["unit"=>["unit was successfully updated"]]));
    }

    /**
     * Remove the specified unit from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->unitService->handleInvalidParameter($id);
        $this->unitService->handleModelNotFound($id);

        $this->unit->find($id)->delete();
        return formatResponse(false,(["unit"=>["unit deleted successfully"]]));
    }
}
