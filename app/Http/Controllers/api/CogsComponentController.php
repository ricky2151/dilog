<?php

namespace App\Http\Controllers\api;

use App\Services\CogsComponentService;
use App\Http\Requests\StoreCogsComponent;
use App\Http\Requests\UpdateCogsComponent;
use App\Models\CogsComponent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CogsComponentController extends Controller
{
    private $cogsComponentService,$cogsComponent;

    public function __construct(CogsComponentService $cogsComponentService, CogsComponent $cogsComponent)
    {
        $this->cogsComponentService = $cogsComponentService;
        $this->cogsComponent = $cogsComponent;
    }
    /**
     * Display a listing of the cogs component.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->cogsComponentService->handleEmptyModel();

        $cogsComponents = $this->cogsComponent->latest()->get();
        return formatResponse(false,(["cogsComponents"=>$cogsComponents]));
    }


    /**
     * Store a newly created cogs component in storage.
     *
     * @param  \Illuminate\Http\StoreCogsComponent  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCogsComponent $request)
    {
        $data = $request->validated();

        $this->cogsComponent->create($data);
        return formatResponse(false,(["cogsComponent"=>["cogsComponent successfully created"]]));
    }

    /**
     * Display the specified cogs component.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->cogsComponentService->handleInvalidParameter($id);
        $this->cogsComponentService->handleModelNotFound($id);

        $cogsComponent = $this->cogsComponent->find($id);
        return formatResponse(false,(["cogsComponent"=>$cogsComponent]));
    }

    /**
     * Update the specified cogs component in storage.
     *
     * @param  \Illuminate\Http\UpdateCogsComponent  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCogsComponent $request, $id)
    {
        $this->cogsComponentService->handleInvalidParameter($id);
        $this->cogsComponentService->handleModelNotFound($id);

        $this->cogsComponent->find($id)->update($request->validated());
        return formatResponse(false,(["cogsComponent"=>["cogsComponent was successfully updated"]]));
    }

    /**
     * Remove the specified cogs component from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->cogsComponentService->handleInvalidParameter($id);
        $this->cogsComponentService->handleModelNotFound($id);

        $this->cogsComponent->find($id)->delete();
        return formatResponse(false,(["cogsComponent"=>["cogsComponent deleted successfully"]]));
    }
}
