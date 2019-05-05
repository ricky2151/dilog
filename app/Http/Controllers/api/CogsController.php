<?php

namespace App\Http\Controllers\api;

use App\Services\CogsService;
use App\Http\Requests\StoreCogs;
use App\Http\Requests\UpdateCogs;
use App\Models\Cogs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CogsController extends Controller
{
    private $cogsService,$cogs;

    public function __construct(CogsService $cogsService, Cogs $cogs)
    {
        $this->cogsService = $cogsService;
        $this->cogs = $cogs;
    }

    /**
     * Display a listing of the cogs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->cogsService->handleEmptyModel();

        $CollectionCogs = $this->cogs->latest()->get();
        return formatResponse(false,(["cogs"=>$CollectionCogs]));
    }


    /**
     * Store a newly created cogs in storage.
     *
     * @param  \Illuminate\Http\StoreCogs  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCogs $request)
    {
        $data = $request->validated();

        $this->cogs->create($data);
        return formatResponse(false,(["cogs"=>["cogs successfully created"]]));
    }

    /**
     * Display the specified cogs.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->cogsService->handleInvalidParameter($id);
        $this->cogsService->handleModelNotFound($id);

        $cogs = $this->cogs->find($id);
        return formatResponse(false,(["cogs"=>$cogs]));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateCogs  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCogs $request, $id)
    {
        $this->cogsService->handleInvalidParameter($id);
        $this->cogsService->handleModelNotFound($id);

        $this->cogs->find($id)->update($request->validated());
        return formatResponse(false,(["cogs"=>["cogs was successfully updated"]]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->cogsService->handleInvalidParameter($id);
        $this->cogsService->handleModelNotFound($id);

        $this->cogs->find($id)->delete();
        return formatResponse(false,(["cogs"=>["cogs deleted successfully"]]));
    }
}
