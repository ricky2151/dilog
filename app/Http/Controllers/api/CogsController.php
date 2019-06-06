<?php

namespace App\Http\Controllers\api;

use App\Services\CogsService;
use App\Http\Requests\StoreCogs;
use App\Http\Requests\UpdateCogs;
use App\Models\Cogs;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;
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

        $CollectionCogs = $this->cogs->index();
        
        return formatResponse(false,(["cogs"=>$CollectionCogs]));
    }

    public function create()
    {
        $this->cogsService->handleGetAllDataForGoodsCreation();
        $data = $this->cogs->allDataCreate();
        return formatResponse(false,($data));
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

        DB::beginTransaction();
        try {
            // $cogsComponent = collect(Arr::pull($data,'cogs_component'))->toArray();

            $cogs = $this->cogs->create($data);
            // $cogs->cogsComponents()->createMany($cogsComponent);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Cogs");
        }
        
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
     * Show the form for editing the specified cogs.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->cogsService->handleInvalidParameter($id);
        $this->cogsService->handleModelNotFound($id);
        $this->cogsService->handleGetAllDataForGoodsCreation();

        $data = collect($this->cogs->allDataCreate());
        $cogs = collect($this->cogs->find($id));

        // $concatenated = $cogs->union($data)->union($this->showFormatData($id));
        $concatenated = $cogs->union($data);

        return formatResponse(false,(["cogs"=>$concatenated]));

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
        $data = $request->validated();
        
        $this->cogsService->handleInvalidParameter($id);
        $this->cogsService->handleModelNotFound($id);

        $cogs = $this->cogs->find($id);

        DB::beginTransaction();
        try { 

            // $cogsComponents = Arr::pull($data,'cogs_component');

            $cogs->update($data);
            // is_null($cogsComponents) ? "" : $cogs->updateCogsComponent($cogsComponents);


            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Cogs");
        }
        
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

    /**
     * Format goods relation data in method show.
     *
     * @param  $id
     * @return Illuminate\Support\Collection
     */
    public function showFormatData($id){
        $cogsComponents = $this->cogs->find($id)->cogsComponents;

        $data = collect(["cogs_components" => $cogsComponents]);

        return $data;
    }
}
