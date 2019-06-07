<?php

namespace App\Http\Controllers\api;

use App\Models\Division;
use App\Services\DivisionService;
use App\Http\Requests\StoreDivision;
use App\Http\Requests\UpdateDivision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisionController extends Controller
{
    private $divisionService, $division;

    public function __construct(DivisionService $divisionService, Division $division)
    {
        $this->divisionService = $divisionService;
        $this->division = $division;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->divisionService->handleEmptyModel();

        $divisions = $this->division->latest()->get();
        return formatResponse(false,(["divisions"=>$divisions]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreDivision  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDivision $request)
    {
        $data = $request->validated();
        $this->division->create($data);
        return formatResponse(false,(["division"=>["division successfully created"]]));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->divisionService->handleInvalidParameter($id);
        $this->divisionService->handleModelNotFound($id);

        $division = $this->division->find($id);
        return formatResponse(false,(["division"=>$division]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->divisionService->handleInvalidParameter($id);
        $this->divisionService->handleModelNotFound($id);

        $division = $this->division->find($id);
        return formatResponse(false,(["division"=>$division]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateDivision  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDivision $request, $id)
    {
        $this->divisionService->handleInvalidParameter($id);
        $this->divisionService->handleModelNotFound($id);

        $this->division->find($id)->update($request->validated());

        return formatResponse(false,(["division"=>["division successfully updated"]]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->divisionService->handleInvalidParameter($id);
        $this->divisionService->handleModelNotFound($id);

        $this->division->find($id)->delete();

        return formatResponse(false,(["division"=>["division deleted successfully"]]));
    }
}
