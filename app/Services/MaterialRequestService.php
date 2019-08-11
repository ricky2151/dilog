<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Exceptions\DivisionNotPermittedException;
use App\Models\MaterialRequest;
use App\Models\User;
use App\Models\Division;
use App\Models\Periode;
use App\Models\Goods;

class MaterialRequestService
{
    private $materialRequest, $user, $goods, $periode;

    public function __construct(MaterialRequest $materialRequest, Goods $goods, Periode $periode)
    {
        $this->materialRequest = $materialRequest;
        $this->goods = $goods;
        $this->periode = $periode;
        $this->user = auth('api')->user();
    }

    public function handleIndex(){
        if($this->user->division->id == 1){
            return $this->financeDivisionIndex();
        }
        else{
            return $this->otherDivisionIndex();
        }
    }

    public function financeDivisionIndex(){
        return collect($this->materialRequest->with(array('user'=>function($query){
            $query->select('id','name');
        },'division'=>function($query){
            $query->select('id','name');
        }))->get())->map(function($item){
            return ['id'=>$item['id'], 
                'code'=>$item['code'], 
                'user_name'=>$item['user']['name'], 
                'division_name'=>$item['division']['name'],
                'status' => $item['status'] == 0 ? "not respon" : "respon"
            ];
        });
    }

    public function otherDivisionIndex(){
        return $this->materialRequest->getMaterialRequestInActivePeriode();
    }

    public function createForm(){
        $goods = $this->goods->select('id','name','avg_price','thumbnail')->get();
        $periode = $this->periode->getPeriodeActive();

        return ["goods"=>$goods,'periode' => $periode];
    }

    public function checkDivision($division){
        if($division['mr_enable'] == 0){
            throw new DivisionNotPermittedException(); 
        }
    }

    public function editForm($id){
        $goods = $this->goods->index();
        $periode = $this->periode->getPeriodeActive();
        $materialRequest = $this->materialRequest->find($id);
        $detailMaterialRequest = $this->materialRequest->find($id)->materialRequestDetails;

        return ["goods"=>$goods,'periode' => $periode, 'material_request' => $materialRequest, 'detail_material_request'=>$detailMaterialRequest];
    }

    public function handleEmptyModel(){
        if(MaterialRequest::all()->count() === 0){
            throw new CustomModelNotFoundException("material_request"); 
        } 

    }
    

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["material_request"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = MaterialRequest::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("material_request"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}