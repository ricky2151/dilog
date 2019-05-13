<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Unit;

class UnitService
{
    public function handleEmptyModel(){
        if(Unit::all()->count() === 0){
            throw new CustomModelNotFoundException("unit"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["unit"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = Unit::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("unit"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}