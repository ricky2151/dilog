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

    public static function index(){
        $collectionCogs = Cogs::latest()->get();

        $collectionCogs = $collectionCogs->map(function ($item) {
            $item = Arr::add($item, 'type_name', $item['type']['name']);
            return Arr::except($item, ['type']);
        });

        return $collectionCogs;
    }

    public function updateCogsComponent($cogsComponents){
        foreach($cogsComponents as $cogsComponent){
            if($cogsComponent['type'] == 1) {
                $this->cogsComponents()->create($cogsComponent);
            }
            else if($cogsComponent['type'] == 0) {
                $this->cogsComponents()->find($cogsComponent['id'])->update($cogsComponent);
            } else {
                $this->cogsComponents()->find($cogsComponent['id'])->delete();
            }
        }
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

    public function getDataAndRelation($id){
        $data = $this->with('type:id,name')->where('id',$id)->first();
        $data = Arr::except($data, ['type_id','created_at','updated_at','deleted_at']);

        return $data;
    }

    public function getMasterData(){
        return [
            'types' => Type::all(['id','name'])
        ];
    }
}
