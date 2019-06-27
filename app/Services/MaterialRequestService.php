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

    public function createForm(){
        $goods = $this->goods->index();
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