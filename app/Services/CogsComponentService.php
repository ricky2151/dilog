<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\CogsComponent;

class CogsComponentService
{
    public function handleEmptyModel(){
        if(CogsComponent::all()->count() === 0){
            throw new CustomModelNotFoundException("cogs_component"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["cogs_component"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = CogsComponent::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("cogs_component"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}