<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Goods;
use App\Models\Material;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Unit;
use App\Models\Cogs;

class GoodsService
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
        if(Goods::all()->count() === 0){
            throw new CustomModelNotFoundException("goods"); 
        } 

    }

    public function handleGetAllDataForGoodsCreation(){
        if(Material::all()->count() === 0){
            throw new CustomModelNotFoundException("material"); 
        } 
        elseif(Category::all()->count() === 0){
            throw new CustomModelNotFoundException("category"); 
        } 
        elseif(Attribute::all()->count() === 0){
            throw new CustomModelNotFoundException("attribute"); 
        } 
        elseif(Unit::all()->count() === 0){
            throw new CustomModelNotFoundException("unit"); 
        } 
        elseif(Cogs::all()->count() === 0){
            throw new CustomModelNotFoundException("cogs"); 
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