<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\ModelDontHaveRelation;
use App\Models\GoodsRack;
use App\Models\Goods;
use App\Models\Rack;
use App\Models\CategoryPriceSelling;
use App\Models\PriceSelling;
use App\Models\Source;


class GoodsRackService
{
    private $goodsRack, $goods, $rack;

    public function __construct(GoodsRack $goodsRack, Goods $goods, Rack $rack)
    {
        $this->goodsRack = $goodsRack;
        $this->goods = $goods;
        $this->rack = $rack;
    }
    
    public function handleGetAllDataForGoodsCreation(){
        if($this->goods->all()->count() === 0){
            throw new CustomModelNotFoundException("good"); 
        } 
        elseif($this->rack->all()->count() === 0){
            throw new CustomModelNotFoundException("rack"); 
        } 
        // elseif(Source::all()->count() === 0){
        //     throw new CustomModelNotFoundException("source"); 
        // } 
    }

    public function handleEmptyModel(){
        if($this->goodsRack->all()->count() === 0){
            throw new CustomModelNotFoundException("goods_rack"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["goods_rack"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $goodsRack = $this->goodsRack->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("goods_rack"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}