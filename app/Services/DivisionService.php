<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Division;

class DivisionService
{
    private $division;

    public function __construct(Division $division)
    {
        $this->division = $division;
    }


    public function handleEmptyModel(){
        if($this->division->all()->count() === 0){
            throw new CustomModelNotFoundException("division"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["division"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $division = $this->division->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("division"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}