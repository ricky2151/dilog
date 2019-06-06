<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Supplier;

class SupplierService
{
    public function handleEmptyModel(){
        if(Supplier::all()->count() === 0){
            throw new CustomModelNotFoundException("source"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["supplier"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = Supplier::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("supplier"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}