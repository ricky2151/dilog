<?php

namespace App\Http\Controllers\api;

use App\Services\PeriodeService;
use App\Http\Requests\StorePeriode;
use App\Http\Requests\UpdatePeriode;
use App\Models\Periode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeriodeController extends Controller
{
    private $periodeService,$periode;

    public function __construct(PeriodeService $periodeService, Periode $periode)
    {
        $this->periodeService = $periodeService;
        $this->periode = $periode;
    }
    /**
     * Display a listing of the periode.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $periodes = $this->periode->latest()->get();
        return formatResponse(false,(["periodes"=>$periodes]));
    }

    

    /**
     * Store a newly created periode in storage.
     *
     * @param  \Illuminate\Http\StorePeriode  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePeriode $request)
    {
        $data = $request->validated();

        $this->periode->create($data);
        return formatResponse(false,(["periode"=>["periode successfully created"]]));
    }

    /**
     * Display the specified periode.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->periodeService->handleInvalidParameter($id);
        $this->periodeService->handleModelNotFound($id);

        $periode = $this->periode->find($id);
        return formatResponse(false,(["periode"=>$periode]));
    }


    /**
     * Update the specified periode in storage.
     *
     * @param  \Illuminate\Http\UpdatePeriode  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePeriode $request, $id)
    {
        $this->periodeService->handleInvalidParameter($id);
        $this->periodeService->handleModelNotFound($id);

        $periode = $this->periode->find($id)->update($request->validated());
        return formatResponse(false,(["periode"=>["periode was successfully updated"]]));
    }

    /**
     * non-activate the specified periode from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->periodeService->handleInvalidParameter($id);
        $this->periodeService->handleModelNotFound($id);

        $periode = $this->periode->find($id)->nonActive();
        return formatResponse(false,(["periode"=>["periode was nonactivated"]]));

    }
}
