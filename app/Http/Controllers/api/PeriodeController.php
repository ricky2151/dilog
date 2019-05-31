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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periodes = $this->periode->latest()->get();
        return formatResponse(false,(["periodes"=>$periodes]));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StorePeriode  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeriode $request)
    {
        $data = $request->validated();

        $this->periode->create($data);
        return formatResponse(false,(["periode"=>["periode successfully created"]]));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->periodeService->handleInvalidParameter($id);
        $this->periodeService->handleModelNotFound($id);

        $periode = $this->periode->find($id);
        return formatResponse(false,(["periode"=>$periode]));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdatePeriode  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePeriode $request, $id)
    {
        $this->periodeService->handleInvalidParameter($id);
        $this->periodeService->handleModelNotFound($id);

        $periode = $this->periode->find($id)->update($request->validated());
        return formatResponse(false,(["periode"=>["periode was successfully updated"]]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Periode $periode)
    {
        //
    }
}
