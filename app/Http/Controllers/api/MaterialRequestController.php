<?php

namespace App\Http\Controllers\api;

use App\Services\MaterialRequestService;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\MaterialRequest;
use App\Models\User;
use App\Models\Division;
use App\Models\Periode;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DatabaseTransactionErrorException;

class MaterialRequestController extends Controller
{
    private $materialRequestService, $materialRequest, $user, $goods, $periode;

    public function __construct(MaterialRequestService $materialRequestService, MaterialRequest $materialRequest, Goods $goods, Periode $periode)
    {
        $this->materialRequestService = $materialRequestService;
        $this->materialRequest = $materialRequest;
        $this->goods = $goods;
        $this->periode = $periode;
        $this->user = auth('api')->user();
    }

    /**
     * Display a listing of the Material Request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->materialRequestService->handleIndex();
    }

    /**
     * Show the form for creating a new Material Request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return formatResponse(false,($this->materialRequestService->createForm()));
    }

    /**
     * Store a newly created resource in Material Request.
     *
     * @param  \Illuminate\Http\StoreMaterialRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMaterialRequest $request)
    {
        $data = $request->validated();
        $this->materialRequestService->checkDivision($this->user->division);

        DB::beginTransaction();
        try {
            $materialRequestDetails = collect(Arr::pull($data,'material_request_details'))->unique(function ($item) {
                return $item['goods_id'];
            })->toArray();

            $data = Arr::add($data, 'division_id', $this->user->division['id']);
            $data = Arr::add($data, 'periode_id', $this->periode->getPeriodeActive()['id']);
            
            $materialRequest = $this->user->materialRequests()->create($data);
            $materialRequest->update(['no_mr'=>"MR-".$materialRequest['id']]);
            $materialRequest->materialRequestDetails()->createMany($materialRequestDetails);

            // return $materialRequest;

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw new DatabaseTransactionErrorException("MaterialRequest");
        }
        return formatResponse(false,(["material_request"=>["Material Request successfully created"]]));

    }

    /**
     * Display the specified Material Request.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->materialRequestService->handleInvalidParameter($id);
        $this->materialRequestService->handleModelNotFound($id);

        return formatResponse(false,(["material_request_details"=>$this->materialRequest->find($id)->materialRequestDetails]));
    }

    /**
     * approve the specified Material Request.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id)
    {
        $this->materialRequestService->handleInvalidParameter($id);
        $this->materialRequestService->handleModelNotFound($id);
        $this->materialRequestService->handleApprove($id);

        return formatResponse(false,(["material_request_details"=>["status changed to approve"]]));
    }

    /**
     * Display list of Material Request Details of the specified Material Request.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function materialRequestDetails($id)
    {
        $this->materialRequestService->handleInvalidParameter($id);
        $this->materialRequestService->handleModelNotFound($id);

        return formatResponse(false,(["material_request_details"=>$this->materialRequest->find($id)->materialRequestDetails]));
    }



    /**
     * Show the form for editing the specified Material Request.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $this->materialRequestService->handleInvalidParameter($id);
        $this->materialRequestService->handleModelNotFound($id);

        return formatResponse(false,(["material_request"=>$this->materialRequestService->editForm($id)]));
    }

    /**
     * Update the specified Material Request in storage.
     *
     * @param  \Illuminate\Http\UpdateMaterialRequest  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateMaterialRequest $request, $id)
    {
        // $this->materialRequestService->handleInvalidParameter($id);
        // $this->materialRequestService->handleModelNotFound($id);

        // $data = $request->validated();

        // DB::beginTransaction();
        // try {
        //     $materialRequestDetails = collect(Arr::pull($data,'material_request_details'))->unique(function ($item) {
        //         return $item['goods_id'];
        //     })->toArray();

        //     $this->materialRequest->find($id)->updateDetailMaterialRequest($materialRequestDetails);

        //     DB::commit();
        // } catch (\Throwable $e) {
        //     DB::rollback();
        //     throw new DatabaseTransactionErrorException("MaterialRequest");
        // }
        // return formatResponse(false,(["material_request"=>["Material Request successfully updated"]]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // DB::beginTransaction();
        // try {
        //     $this->materialRequest->find($id)->materialRequestDetails()->delete();
        //     $this->materialRequest->find($id)->delete();

        //     DB::commit();
        // } catch (\Throwable $e) {
        //     DB::rollback();
        //     return $e;
        //     throw new DatabaseTransactionErrorException("MaterialRequest");
        // }
        // return formatResponse(false,(["material_request"=>["Material Request deleted successfully"]]));
    }
}
