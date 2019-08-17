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
use Illuminate\Support\Arr;

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
        if($this->user->division_id == 1){
            return $this->financeDivisionIndex();
        }
        else{
            return $this->otherDivisionIndex();
        }
    }

    public function financeDivisionIndex(){
        $data = collect($this->materialRequest->with(array('user'=>function($query){
            $query->select('id','name');
        },'division'=>function($query){
            $query->select('id','name');
        }))->get())->map(function($item){
            return ['id'=>$item['id'], 
                'code'=>$item['code'], 
                'user_name'=>$item['user']['name'], 
                'division_name'=>$item['division']['name'],
                'status' => $item->getStatusName(),
                'created_at' => $item['created_at']->format('Y-m-d'),
                'periode_id' => $item['periode_id'],
            ];
        })->sortByDesc('created_at')->values();

        return formatResponse(false,(["material_request"=>$data,"periode_active"=>$this->periode->getPeriodeActive(),'periodes'=>$this->periode->latest()->get()]));
    }

    public function otherDivisionIndex(){
        // return $this->user->makeVisible('id')->toArray();
        return formatResponse(false,([
            "user_info"=>[
                'user_name'=>$this->user->name,
                'user_email'=> $this->user->email,
                'total_mr' => $this->user->getTotalMrRp(),
                'count_mr' => $this->user->materialRequests->count()
            ],
            "material_requests" => $this->user->materialRequests->where('periode_id',$this->periode->getPeriodeActive()['id'])->map(function($item){
                $item['status'] = $item->getStatusName();
                $item = collect(Arr::add($item,'total', $item->getTotal()));
                return Arr::except($item,['material_request_details']);
            })->values()
        ]));
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

    public function handleApprove($id){
        $materialRequest = $this->materialRequest->find($id);
        if($this->user->division_id != 1)
            throw new InvalidParameterException(json_encode(["material_request"=>["user is not permitted to approve material request"]]));
        if($materialRequest['status'] != 0)
            throw new InvalidParameterException(json_encode(["material_request"=>["can't change status to approve because the status is not new"]]));
        $materialRequest->approve();    
    }

    public function editForm($id){
        $goods = $this->goods->index();
        $periode = $this->periode->getPeriodeActive();
        $materialRequest = $this->materialRequest->find($id);
        $detailMaterialRequest = $this->materialRequest->find($id)->materialRequestDetails;

        return ["goods"=>$goods,'periode' => $periode, 'material_request' => $materialRequest, 'detail_material_request'=>$detailMaterialRequest];
    }

    public function handleEmptyModel(){
        if($this->materialRequest->all()->count() === 0){
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
            $materialRequest = $this->materialRequest->findOrFail($id);
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