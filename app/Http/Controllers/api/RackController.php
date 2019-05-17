<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\StoreRack;
use App\Http\Requests\UpdateRack;
use App\Services\RackService;
use App\Models\Rack;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RackController extends Controller
{
    private $rackService,$rack;

    public function __construct(RackService $rackService, Rack $rack)
    {
        $this->rackService = $rackService;
        $this->rack = $rack;
    }
    /**
     * Display a listing of the rack.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->rackService->handleEmptyModel();

        $racks = $this->rack->latest()->get();
        return formatResponse(false,(["racks"=>$racks]));
    }

    /**
     * Store a newly created rack in storage.
     *
     * @param  \Illuminate\Http\StoreRack  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRack $request)
    {
        $data = $request->validated();

        $this->rack->create($data);
        return formatResponse(false,(["rack"=>["rack successfully created"]]));
    }

    /**
     * Display the specified rack.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);

        $rack = $this->rack->find($id);
        return formatResponse(false,(["rack"=>$rack]));
    }

    /**
     * Update the specified rack in storage.
     *
     * @param  \Illuminate\Http\UpdateRack  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRack $request, $id)
    {
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);

        $this->rack->find($id)->update($request->validated());
        return formatResponse(false,(["rack"=>["rack was successfully updated"]]));
    }

    /**
     * Remove the specified rack from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->rackService->handleInvalidParameter($id);
        $this->rackService->handleModelNotFound($id);
        DB::beginTransaction();
        try {
            $this->rack->find($id)->goodsRack()->delete();
            $this->rack->find($id)->delete();

            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("Rack");
        }
        
        return formatResponse(false,(["rack"=>["rack deleted successfully"]]));
    }
}
