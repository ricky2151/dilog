<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\CategoryPriceSelling;

class CategoryPriceSellingService
{
    public function handleEmptyModel(){
        if(CategoryPriceSelling::all()->count() === 0){
            throw new CustomModelNotFoundException("category_price_selling"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["category_price_selling"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = CategoryPriceSelling::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("category_price_selling"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}