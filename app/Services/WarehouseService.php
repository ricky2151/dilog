<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\ModelDontHaveRelation;
use App\Models\Warehouse;
use App\Models\Rack;
use Illuminate\Support\Arr;
use Storage;

class WarehouseService
{
    private $warehouse;

    public function __construct(Warehouse $warehouse, Rack $rack)
    {
        $this->warehouse = $warehouse;
        $this->rack = $rack;
    }

    public function checkRelationship($warehouseId,$data){
        foreach($data as $id){
            try{
                $rack = Rack::findOrFail($id);
            }catch(ModelNotFoundException $e){
                throw new CustomModelNotFoundException("Rack"); 
            }
            if($rack->warehouse_id != $warehouseId){
                throw new ModelDontHaveRelation(json_encode([0=>"Rack",1=>"Warehouse"])); 
            }
        }
    }

    public function handleCreate($data){
        if($data['store_type'] == 1){
            $warehouse = $this->warehouse->create($data);
            foreach($data['copy_racks'] as $rack){
                $oldRack = $this->rack->find($rack['id']);
                if($oldRack['warehouse_id'] == $data['warehouse_id']){
                    $newRack = $warehouse->racks()->create(collect($oldRack)->except(['warehouse_id'])->toArray());
                    if($rack['is_with_goods']){
                        $goods = collect($oldRack->goodsRack)->map(function ($data) {
                            return ['stock'=>$data['stock'],'goods_id'=>$data['goods_id']];
                        })->toArray();
                        $newRack->goodsRack()->createMany($goods);
                    } 
                }
            }
        }
        else{
            $racks = Arr::pull($data,'racks');
            $warehouse = $this->warehouse->create($data);
            $warehouse->racks()->createMany($racks);
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