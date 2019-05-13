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

        $units = $this->unit->latest()->get();
        return formatResponse(false,(["units"=>$units]));
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
