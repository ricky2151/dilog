<?php

namespace App\Http\Controllers\api;

use App\Models\Spbm;
use App\Models\PurchaseOrder;
use App\Models\GoodsRack;
use App\Services\SpbmService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSpbm;
use App\Http\Requests\StoreSpbm;
use Illuminate\Support\Facades\DB;
use App\Exceptions\DatabaseTransactionErrorException;

class SpbmController extends Controller
{
    private $spbm, $purchaseOrder, $spbmService, $goodsRack;

    public function __construct(PurchaseOrder $purchaseOrder, Spbm $spbm, SpbmService $spbmService, GoodsRack $goodsRack)
    {
        $this->spbm = $spbm;
        $this->purchaseOrder = $purchaseOrder;
        $this->spbmService = $spbmService;
        $this->goodsRack = $goodsRack;
    }

    /**
     * Display a listing of the spbm.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new spbm.
     *@param  \Illuminate\Http\CreateSpbm  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateSpbm $request)
    {
        $validated = $request->validated();
        $this->spbmService->isValidOpenSpbm($validated['purchase_order_id']);
        $spbm = $this->spbm->getMasterData($validated['purchase_order_id']);
        return formatResponse(false,($spbm));
    }

    /**
     * Store a newly created spbm in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSpbm $request)
    {
        $validated = $request->validated();
        $this->spbmService->isValidStoreSpbm($validated['purchase_order_id']);
        $validated['arrival_date'] = now();
        $validated['spbm_details'] = $this->spbmService->cleanDataSpbmDetail($validated['spbm_details'], $validated['purchase_order_id']);

        DB::beginTransaction();
        try {
            $spbm = $this->spbm->create($validated);
            $spbm->spbmDetails()->createMany($validated['spbm_details']->toArray());
            $this->purchaseOrder->find($validated['purchase_order_id'])->setPersenComplete();
            $goodsRacks = $validated['spbm_details']->map(function($item){
                $goodsRack = $this->goodsRack->where([['goods_id',$item['goods_id']],['rack_id',$item['rack_id']]])->first();
                if(is_null($goodsRack)){
                    $this->goodsRack->create([
                        'goods_id' => $item['goods_id'],
                        'rack_id' => $item['rack_id'],
                        'stock' => $item['qty'], 
                    ]);
                }
                else{
                    $goodsRack->update(["stock"=>$goodsRack['stock']+$item['qty']]);
                }
            });
            
            

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            return $e;
            throw new DatabaseTransactionErrorException("spbm");
        }
        return formatResponse(false,(["spbm"=>["spbm successfully created"]]));

    }

    /**
     * Display the specified spbm.
     *
     * @param  \App\Models\Spbm  $spbm
     * @return \Illuminate\Http\Response
     */
    public function show(Spbm $spbm)
    {
        //
    }

    /**
     * Show the form for editing the specified spbm.
     *
     * @param  \App\Models\Spbm  $spbm
     * @return \Illuminate\Http\Response
     */
    public function edit(Spbm $spbm)
    {
        //
    }

    /**
     * Update the specified spbm in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Spbm  $spbm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spbm $spbm)
    {
        //
    }

    /**
     * Remove the specified spbm from storage.
     *
     * @param  \App\Models\Spbm  $spbm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spbm $spbm)
    {
        //
    }
}
