<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\ModelDontHaveRelation;
use App\Models\Cogs;
use App\Models\CogsComponent;
use App\Models\Type;

class CogsService
{
    private $cogs, $type;

    public function __construct(Cogs $cogs, Type $type)
    {
        $this->cogs = $cogs;
        $this->type = $type;
    }

    public function checkRelationship($cogsId,$data){
        foreach($data as $id){
            try{
                $CogsComponent = CogsComponent::findOrFail($id);
            }catch(ModelNotFoundException $e){
                throw new CustomModelNotFoundException("Cogs Component"); 
            }
            if($CogsComponent->cogs_id != $cogsId){
                throw new ModelDontHaveRelation(json_encode([0=>"Cogs Component",1=>"Cogs"])); 
            }
        }
    }

    public function handleEmptyModel(){
        if($this->cogs->all()->count() === 0){
            throw new CustomModelNotFoundException("cogs"); 
        } 

    }

    public function handleGetAllDataForCogsCreation(){
        if($this->type->all()->count() === 0){
            throw new CustomModelNotFoundException("type"); 
        }
    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["cogs"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $cogs = $this->cogs->findOrFail($id);
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