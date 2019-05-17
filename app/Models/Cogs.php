<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Cogs extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'nominal','name','type_id'
    ];

    public function updateManyAtribut($cogs_components_update){
        if(!is_null($cogs_components_update)){
            foreach ($cogs_components_update as $update) {
                $data = Arr::except($update, ['id']);
                $this->cogsComponents()->find($update["id"])->update($data);
            }
        }
    }

    public function deleteManyAtribut($cogs_components_delete){
        if(!is_null($cogs_components_delete)){
            foreach ($cogs_components_delete as $delete) {
                $this->cogsComponents()->find($delete["id"])->delete();
            }
        }
    }

    public static function allDataCreate(){
        return ['type' => Type::all(['id','name'])];
    }

    public function type(){
        return $this->belongsTo('App\Models\Type');
    }

    public function goods(){
        return $this->hasMany('App\Models\Goods');
    }

    public function cogsComponents(){
        return $this->hasMany('App\Models\CogsComponent');
    }
}
