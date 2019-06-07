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
    public function checkRelationship($goodsRackId,$data){
        foreach($data as $id){
            try{
                $priceSelling = PriceSelling::findOrFail($id);
            }catch(ModelNotFoundException $e){
                throw new CustomModelNotFoundException("price_selling"); 
            }
            if($priceSelling->goods_rack_id != $goodsRackId){
                throw new ModelDontHaveRelation(json_encode([0=>"Price Selling",1=>"Goods Rack"])); 
            }
        }
    }
    
    public function handleGetAllDataForGoodsCreation(){
        if(Goods::all()->count() === 0){
            throw new CustomModelNotFoundException("good"); 
        } 
        elseif(Rack::all()->count() === 0){
            throw new CustomModelNotFoundException("rack"); 
        } 
        elseif(CategoryPriceSelling::all()->count() === 0){
            throw new CustomModelNotFoundException("category_price_selling"); 
        } 
        elseif(Source::all()->count() === 0){
            throw new CustomModelNotFoundException("source"); 
        } 
    }

    public function handleEmptyModel(){
        if(GoodsRack::all()->count() === 0){
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
            $user = GoodsRack::findOrFail($id);
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