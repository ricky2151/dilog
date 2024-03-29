<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Type;

class TypeService
{
    private $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    public function handleEmptyModel(){
        if($this->type->all()->count() === 0){
            throw new CustomModelNotFoundException("type"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["type"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $type = $this->type->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("type"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}