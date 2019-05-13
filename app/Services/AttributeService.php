<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Attribute;

class AttributeService
{
    public function handleEmptyModel(){
        if(Attribute::all()->count() === 0){
            throw new CustomModelNotFoundException("attribute"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["attribute"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = Attribute::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("attribute"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}