<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\MaterialRequest;

class MaterialRequestService
{
    public function handleEmptyModel(){
        if(MaterialRequest::all()->count() === 0){
            throw new CustomModelNotFoundException("material_request"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["material_request"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = MaterialRequest::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("material_request"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}