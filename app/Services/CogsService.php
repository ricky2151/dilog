<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Cogs;

class CogsService
{
    public function handleEmptyModel(){
        if(Cogs::all()->count() === 0){
            throw new CustomModelNotFoundException("cogs"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["cogs"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = Cogs::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("cogs"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}