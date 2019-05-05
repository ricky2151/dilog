<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Source;

class SourceService
{
    public function handleEmptyModel(){
        if(Source::all()->count() === 0){
            throw new CustomModelNotFoundException("source"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["source"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = Source::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("source"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}