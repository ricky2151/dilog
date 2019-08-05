<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Material;

class MaterialService
{
    private $material;

    public function __construct(Material $material)
    {
        $this->material = $material;
    }

    public function handleEmptyModel(){
        if($this->material->all()->count() === 0){
            throw new CustomModelNotFoundException("material"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["material"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = $this->material->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("material"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}