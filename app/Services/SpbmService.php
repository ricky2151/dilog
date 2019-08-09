<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Spbm;

class SpbmService
{
    private $spbm;

    public function __construct(Spbm $spbm)
    {
        $this->spbm = $spbm;
    }

    public function handleEmptyModel(){
        if($this->spbm->all()->count() === 0){
            throw new CustomModelNotFoundException("spbm"); 
        } 
    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["spbm"=>["parameter invalid"]]));
        }
    }

    /**
     * Remove Data detail spbm when have quantity 0
     */
    public function cleanDataSpbmDetail($data){
        $data = collect($data)->filter(function ($item) {
            return $item['qty'] > 0;
        });

        return $data;
    }

    public function handleModelNotFound($id){
        try{
            $spbm = $this->spbm->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("spbm"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}