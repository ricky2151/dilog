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
    protected $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    /**
     * Display a listing of the unit.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->unitService->handleEmptyModel();

        $units = Unit::all();
        return $this->formatResponse(false,(["units"=>$units]));
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
        $unit = new Unit;
        $unit = $unit->create($data);

        return $this->formatResponse(false,(["unit"=>["unit successfully created"]]));
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

        $unit = Unit::find($id);
        return $this->formatResponse(false,(["unit"=>$unit]));
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

        $unit = Unit::find($id);
        $unit->update($request->validated());
        return $this->formatResponse(false,(["unit"=>["unit was successfully updated"]]));
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
        
        $unit = Unit::find($id);
        $unit->delete();
        return $this->formatResponse(false,(["unit"=>["unit deleted successfully"]]));
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
