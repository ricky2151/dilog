<?php

namespace App\Http\Controllers\api;

use App\models\PurchaseRequestDetail;
use App\models\Pricelist;
use App\Http\Requests\UpdatePurchaseRequestDetail;
use App\Services\PurchaseRequestDetailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseRequestDetailController extends Controller
{
    private $purchaseRequestDetailService, $purchaseRequestDetail, $user, $pricelist;

    public function __construct(PurchaseRequestDetailService $purchaseRequestDetailService, PurchaseRequestDetail $purchaseRequestDetail, Pricelist $pricelist)
    {
        $this->purchaseRequestDetailService = $purchaseRequestDetailService;
        $this->purchaseRequestDetail = $purchaseRequestDetail;
        $this->pricelist = $pricelist;
        $this->user = auth('api')->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return formatResponse(false,([
            "purchase_request_details"=>$this->purchaseRequestDetail->latest()->get()
        ]));
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
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->purchaseRequestDetailService->handleInvalidParameter($id);
        $this->purchaseRequestDetailService->handleModelNotFound($id);
        return formatResponse(false,([
            "purchase_request_detail"=>$this->purchaseRequestDetail->find($id)->load(['goods','purchaseRequest','supplier','pricelist'])
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->purchaseRequestDetailService->handleInvalidParameter($id);
        $this->purchaseRequestDetailService->handleModelNotFound($id);

        $purchaseRequestDetail = $this->purchaseRequestDetail->find($id)->load(['goods','purchaseRequest','supplier','pricelist']);
        return formatResponse(false,([
            "purchase_request_detail"=>collect($purchaseRequestDetail),
            'master_data'=>$purchaseRequestDetail->getMasterData()
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdatePurchaseRequestDetail  $request
     * @param  Interger  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequestDetail $request, $id)
    {
        $this->purchaseRequestDetailService->handleInvalidParameter($id);
        $this->purchaseRequestDetailService->handleModelNotFound($id);

        $data = $request->validated();
        $purchaseRequestDetail = $this->purchaseRequestDetail->find($id);
        if($purchaseRequestDetail['is_created_as_po'] == 1){
            return formatResponse(true,(["purchaseRequestDetail"=>"can't edit because this detail already be po"]));
        }

        //Check Supplier
        // return $purchaseRequestDetail->goods->pricelists;
        if (!$purchaseRequestDetail->goods->pricelists->contains(function($array,$key) use($data){
            return $array['supplier_id'] == $data['supplier_id'] && $array['id'] == $data['pricelist_id'];
        })){
            return formatResponse(true,(["purchaseRequestDetail"=>"supplier id and pricelist id not match to goods id"]));
        }
        else{
            // return $purchaseRequestDetail->goods->pricelists->firstWhere('id',$data['pricelist_id'])['price'];
            if($purchaseRequestDetail->goods->pricelists->firstWhere('id',$data['pricelist_id'])['price'] != $data['price']){
                return formatResponse(true,(["price"=>" price not match to pricelist id"]));
            }
            else{
                $purchaseRequestDetail->update($data);
                return formatResponse(false,(["purchaseRequestDetail"=>"purchase request detail was successfully updated"]));
            }
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\PurchaseRequestDetail  $purchaseRequestDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->purchaseRequestDetailService->handleInvalidParameter($id);
        $this->purchaseRequestDetailService->handleModelNotFound($id);

        $purchaseRequestDetail = $this->purchaseRequestDetail->find($id);
        if($purchaseRequestDetail->is_created_as_po == 0){
            $purchaseRequestDetail->delete();
            return formatResponse(false,(["purchaseRequestDetail"=>"successfull delete"]));
        }
        else{
            return formatResponse(true,(["purchaseRequestDetail"=>"cannot be deleted because it has been made into PO"]));
        }
    }
}
