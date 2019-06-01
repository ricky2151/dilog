<?php

namespace App\Http\Controllers\api;

use App\Services\MaterialRequestService;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\MaterialRequest;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialRequestController extends Controller
{
    private $materialRequestService,$materialRequest;

    public function __construct(MaterialRequestService $materialRequestService, MaterialRequest $materialRequest)
    {
        $this->materialRequestService = $materialRequestService;
        $this->materialRequest = $materialRequest;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materialRequests = $this->materialRequest->latest()->get();
        $user = User::find(1);
        $materialRequest = $this->materialRequest->find(2)->pic_user_name;
        return $materialRequest;
        // $materialRequests = $materialRequests->map(function ($materialRequest) { 
        //     $materialRequest = Arr::add($materialRequest, 'warehouse_name', $materialRequest['warehouse']['name']);
        //     return Arr::except($materialRequest, ['warehouse']);
        // });

        return formatResponse(false,(["materialRequests"=>$materialRequests]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialRequest  $materialRequest
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialRequest $materialRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialRequest  $materialRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialRequest $materialRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialRequest  $materialRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialRequest $materialRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialRequest  $materialRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialRequest $materialRequest)
    {
        //
    }
}
