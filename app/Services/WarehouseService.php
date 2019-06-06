<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\ModelDontHaveRelation;
use App\Models\Warehouse;
use App\Models\StockOpname;
use App\Models\Goods;
use App\Models\Rack;
use Illuminate\Support\Arr;
use Storage;

class WarehouseService
{
    private $warehouse, $racks, $goods, $stockOpname;

    public function __construct(Warehouse $warehouse, Rack $rack, Goods $goods, StockOpname $stockOpname)
    {
        $this->warehouse = $warehouse;
        $this->rack = $rack;
        $this->goods = $goods;
        $this->stockOpname = $stockOpname;
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

    public function createForm(){
        return ['warehouses' => $this->warehouse->all()->map(function($item){
            $item['racks'] = $item->getRackWithHaveGoods();
            return $item;
        })];
    }

    public function storeStockOpnamesDetails($data, $goodsWithStock, $stockOpnamesId){

        $data = collect(Arr::pull($data,'detail_stock_opname'))->unique(function ($item) {
            return $item['goods_id'];
        });

        $stockOpname = $this->stockOpname->find($stockOpnamesId);

        if($stockOpname['approve'] == 0){
            $goodsWithStock = $goodsWithStock->map(function($item) use($data){
                $newStock = $data->where('goods_id', $item['goods_id'])->flatten();
                $item = Arr::add($item,'new_stock', $newStock->isNotEmpty() ? $newStock[1] : 0);
                $item = Arr::add($item,'notes',$newStock->isNotEmpty() ? $newStock[2] : "tidak diisi stock barunya");
                return $item;
            })->toArray();
    
            $this->stockOpname->find($stockOpnamesId)->stockOpnameDetails()->delete();
            $this->stockOpname->find($stockOpnamesId)->stockOpnameDetails()->createMany($goodsWithStock);

            return "details stock opname in specific warehouse successfully created";
        }
        else{
            return "Data baru tidak bisa disimpan karena sudah tersubmit";
        }
    
    }

    public function updateStockOpnamesDetails($data, $goodsWithStock, $stockOpnamesId){

        $data = collect(Arr::pull($data,'detail_stock_opname'))->unique(function ($item) {
            return $item['goods_id'];
        });


        $stockOpname = $this->stockOpname->find($stockOpnamesId);

        if($stockOpname['approve'] == 0){
            $goodsWithStock = $goodsWithStock->map(function($item) use($data, $stockOpnamesId){
                $newStock = $data->where('goods_id', $item['goods_id'])->flatten();
                $item = Arr::set($item,'new_stock', $newStock->isNotEmpty() ? $newStock[1] : 0);
                $item = Arr::set($item,'notes',$newStock->isNotEmpty() ? $newStock[2] : "tidak diisi stock barunya");

                $dataUpdate = $item->toArray();
                $this->stockOpname->find($stockOpnamesId)->stockOpnameDetails->find($item)->update($dataUpdate);
                return $item;
            })->toArray();
            
            return $goodsWithStock;

            $this->stockOpname->find($stockOpnamesId)->stockOpnameDetails()->delete();
            $this->stockOpname->find($stockOpnamesId)->stockOpnameDetails()->createMany($goodsWithStock);

            return "details stock opname in specific warehouse successfully created";
        }
        else{
            return "Data baru tidak bisa disimpan karena sudah tersubmit";
        }
    
    }

    public function detailStockOpnames($stockOpnamesId){
        return collect($this->stockOpname->find($stockOpnamesId)->stockOpnameDetails)->map(function($item){
            $item = Arr::add($item, 'goods_name',$item['goods']['name']);
            return Arr::except($item,['goods']);
        });
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

    public function handleModelStockOpnameNotFound($id){
        try{
            $user = StockOpname::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("stock_opname"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}