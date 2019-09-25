<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Customer;

class CustomerService
{
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function handleEmptyModel(){
        if($this->customer->all()->count() === 0){
            throw new CustomModelNotFoundException("customer"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["customer"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $customer = $this->customer->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("customer"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}