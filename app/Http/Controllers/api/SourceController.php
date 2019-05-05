<?php

namespace App\Http\Controllers\api;

use App\Services\SourceService;
use App\Http\Requests\StoreSource;
use App\Http\Requests\UpdateSource;
use App\Models\Source;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SourceController extends Controller
{
    private $sourceService, $source;

    public function __construct(SourceService $sourceService, Source $source)
    {
        $this->sourceService = $sourceService;
        $this->source = $source;
    }
    /**
     * Display a listing of the source.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->sourceService->handleEmptyModel();

        $sources = $this->source->latest()->get();
        return formatResponse(false,(["source"=>$sources]));
    }

    /**
     * Store a newly created resource in source.
     *
     * @param  \Illuminate\Http\StoreSource  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSource $request)
    {
        $data = $request->validated();

        $this->source->create($data);
        return formatResponse(false,(["source"=>["source successfully created"]]));
    }

    /**
     * Display the specified source.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->sourceService->handleInvalidParameter($id);
        $this->sourceService->handleModelNotFound($id);

        $source = $this->source->find($id);
        return formatResponse(false,(["source"=>$source]));
    }

    /**
     * Update the specified source in storage.
     *
     * @param  \Illuminate\Http\UpdateSource  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSource $request, $id)
    {
        $this->sourceService->handleInvalidParameter($id);
        $this->sourceService->handleModelNotFound($id);

        $this->source->find($id)->update($request->validated());
        return formatResponse(false,(["source"=>["source was successfully updated"]]));
    }

    /**
     * Remove the specified source from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->sourceService->handleInvalidParameter($id);
        $this->sourceService->handleModelNotFound($id);

        $this->source->find($id)->delete();
        return formatResponse(false,(["source"=>["source deleted successfully"]]));
    }
}
