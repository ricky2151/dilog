<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\User;

class UserService
{
    public function handleInvalidParameter($id, int $code){
        if(!$this->isParameterValid($id,$code)){
            throw new InvalidParameterException(json_encode(["user"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $user = User::findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("user"); 
        }
    }

    public function handleModelNotFoundByEmail($email){
        if(User::findEmail($email) == null){
            throw new CustomModelNotFoundException("user"); 
        }
    }

    public function isParameterValid($id, int $code){
        switch($code){
            case 1 : return (is_numeric($id));break;
            case 2 : return (filter_var($id, FILTER_VALIDATE_EMAIL));break;
        }  
    }
}