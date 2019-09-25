<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Unit;

class UnitService
{
    private $unit;

    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
    }

    public function handleEmptyModel(){
        if($this->unit->all()->count() === 0){
            throw new CustomModelNotFoundException("unit"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["unit"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $unit = $this->unit->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("unit"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}