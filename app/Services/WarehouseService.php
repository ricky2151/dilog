<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Warehouse;
use Storage;

class WarehouseService
{
    public function handleUploadImage($image, string $path, string $name){
        if(!is_null($image)){
            $name = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','',$name));
            return storeImage($image,$path,(uniqid().$name.".".$image->getClientOriginalExtension()));
        }
    }

    public function handleUpdateImage($image, string $path, string $name, string $oldPic, $newName){
        if(!is_null($image)){
            deleteImage($oldPic);
            $name = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','',is_null($newName) == true ? $name : $newName));
            return storeImage($image,$path,(uniqid().$name.".".$image->getClientOriginalExtension()));
        }
    }


    public function handleEmptyModel(){
        if(Warehouse::all()->count() === 0){
            throw new CustomModelNotFoundException("warehouse"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["warehouse"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = Warehouse::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("warehouse"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}