<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\ModelDontHaveRelation;
use App\Models\Goods;
use App\Models\Material;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Unit;
use App\Models\Cogs;

class GoodsService
{
    public function checkRelationship($goodsId,$data){
        foreach($data as $id){
            try{
                $material = Material::findOrFail($id);
            }catch(ModelNotFoundException $e){
                throw new CustomModelNotFoundException("Material"); 
            }
            if($material->goods_id != $goodsId){
                throw new ModelDontHaveRelation(json_encode([0=>"Material",1=>"Goods"])); 
            }
        }
    }

    public function handleUploadImage($image, string $path, string $name){
        if(!is_null($image)){
            $name = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','',$name));
            return storeImage($image,$path,(uniqid().$name.".".$image->getClientOriginalExtension()));
        }
        else{
            return $path."/default.png";
        }
    }

    public function handleUpdateImageGetPath($image, string $name, $newName, bool $isDeleteImage){
        if(!is_null($image)){
            $name = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','',is_null($newName) == true ? $name : ($newName.Str::random(10))));
            return (uniqid().$name.".".$image->getClientOriginalExtension());
        }
        else{
            if($isDeleteImage){
                return "default.png";
            }
        }
    }

    public function handleUpdateImage($image, string $oldPic, string $name, string $path,bool $isDeleteImage){
        if(!is_null($image)){
            deleteImage($oldPic);
            storeImage($image,$path,($name));
        }
        else{
            if($isDeleteImage){
                deleteImage($oldPic);
            }
        }
    }
    
    public function handleEmptyModel(){
        if(Goods::all()->count() === 0){
            throw new CustomModelNotFoundException("goods"); 
        } 
    }

    public function handleGetAllDataForGoodsCreation(){
        if(Category::all()->count() === 0){
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