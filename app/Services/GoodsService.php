<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Goods;
use App\Models\Material;
use App\Models\Category;
use App\Models\Attribute;

class GoodsService
{
    public function handleEmptyModel(){
        if(Goods::all()->count() === 0){
            throw new CustomModelNotFoundException("goods"); 
        } 

    }

    public function handleGetAllDataForGoodsCreation(){
        if(Material::all()->count() === 0){
            throw new CustomModelNotFoundException("material"); 
        } 
        if(Category::all()->count() === 0){
            throw new CustomModelNotFoundException("category"); 
        } 
        if(Attribute::all()->count() === 0){
            throw new CustomModelNotFoundException("attribute"); 
        } 
    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["goods"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = Goods::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("goods"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}