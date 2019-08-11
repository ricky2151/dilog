<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\DivisionNotPermittedException;
use App\Models\PurchaseRequest;
use App\Models\MaterialRequest;
use Illuminate\Support\Arr;
use App\Models\Goods;

class PurchaseRequestService
{

    public function handleEmptyModel(){
        if(PurchaseRequest::all()->count() === 0){
            throw new CustomModelNotFoundException("purchase_request"); 
        } 
    }

    public function handleCreateForm($materialRequests){
        if(is_null($materialRequests)){
            return formatResponse(true,(["purchase_request"=>["material request empty"]]));
        }
        else{
            $data = collect($materialRequests)->map(function($item){
                $item = MaterialRequest::find($item['id'])->materialRequestDetails;
                return $item;
            })->flatten(1)->groupBy('goods_id')->map(function($key, $item){
                $goods = Goods::find($item);
                return [
                    "goods_id" => $item, 
                    'goods_name'=>$goods['name'],
                    'total_requested_by_MR' => $key->sum('qty'),
                    'stock'=> $goods->stock(),
                    'total_already_po' => 0,
                    'suppliers' => $goods->suppliers->unique(function ($item) {
                        return $item['id'];
                    })->flatten(1)->map(function($item){
                        return Arr::except($item,['pivot']);
                    }),
                    'pricelists' => $goods->pricelists    
                ];
            })->sortBy('goods_id');

            return formatResponse(false,(["purchase_request"=>$data->values()]));
        }
    }
    

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["purchase_request"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = PurchaseRequest::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("purchase_request"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}