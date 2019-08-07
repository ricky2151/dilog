<?php
namespace App\Services;

use App\Exceptions\InvalidParameterException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotFoundException as CustomModelNotFoundException;
use App\Models\Category;

class CategoryService
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function handleEmptyModel(){
        if($this->category->all()->count() === 0){
            throw new CustomModelNotFoundException("category"); 
        } 

    }

    public function handleInvalidParameter($id){
        if(!$this->isParameterValid($id)){
            throw new InvalidParameterException(json_encode(["category"=>["parameter invalid"]]));
        }
    }

    public function handleModelNotFound($id){
        try{
            $category = $this->category->findOrFail($id);
        }
        catch(ModelNotFoundException $e)
        {
            throw new CustomModelNotFoundException("category"); 
        }
    }

    public function isParameterValid($id){
        return (is_numeric($id));
    }
}