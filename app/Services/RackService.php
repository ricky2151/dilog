<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\ModelDontHaveRelation;
use App\Models\Rack;
use App\Models\Warehouse;
use App\Models\GoodsRack;
use App\Models\Goods;

class RackService
{
    private $rack, $warehouse, $goodsRack;

    public function __construct(Rack $rack, Warehouse $warehouse, GoodsRack $goodsRack)
    {
        $this->rack = $rack;
        $this->warehouse = $warehouse;
        $this->goodsRack = $goodsRack;
    }

    public function checkRelationship($rackId,$data){
        foreach($data as $id){
            try{
                $goodsRack = $this->goodsRack->findOrFail($id);
            }catch(ModelNotFoundException $e){
                throw new CustomModelNotFoundException("Goods Rack"); 
            }
            if($goodsRack->rack_id != $rackId){
                throw new ModelDontHaveRelation(json_encode([0=>"Goods Rack",1=>"Rack"])); 
            }
        }
    }

    public function handleGetAllDataForGoodsCreation(){
        if($this->warehouse->all()->count() === 0){
            throw new CustomModelNotFoundException("warehouse"); 
        }
    }

    public function handleEmptyModel(){
        if($this->rack->all()->count() === 0){
            throw new CustomModelNotFoundException("rack"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["rack"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $rack = $this->rack->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("rack"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}