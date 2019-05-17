<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Http\Traits\Uuids;

class Goods extends Model
{
    use SoftDeletes,Uuids;
    
    protected $fillable = [
        'name','code','desc','margin','value','status','last_buy_pricelist','barcode_master','thumbnail','avgprice_status','user_id','tax','unit_id','cogs_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'uuid'
    ];

    public function updateManyAtribut($material_goods_update){
        if(!is_null($material_goods_update)){
            foreach ($material_goods_update as $update) {
                $data = Arr::except($update, ['id']);
                $this->materials()->find($update["id"])->update($data);
            }
        }
    }

    public function deleteManyAtribut($material_goods_delete){
        if(!is_null($material_goods_delete)){
            foreach ($material_goods_delete as $delete) {
                $this->materials()->find($delete["id"])->delete();
            }
        }
    }

    public static function allDataCreate(){
        return ['categories' => Category::all(['id','name']),'attributes' => Attribute::all(['id','name']),'units'=>Unit::all(['id','name']),'cogs'=>Cogs::all(['id','name'])];
    }

    public function goodsRack(){
        return $this->hasMany('App\Models\GoodsRack');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function unit(){
        return $this->belongsTo('App\Models\Unit');
    }

    public function cogs(){
        return $this->belongsTo('App\Models\Cogs');
    }

    public function categories(){
        return $this->belongsToMany('App\Models\Category','category_goods','goods_id','category_id')->withTimestamps()->orderBy('pivot_updated_at', 'desc');
    }

    public function attributes(){
        return $this->belongsToMany('App\Models\Attribute','attribute_goods','goods_id','attribute_id')->withPivot('value')->withTimestamps()->orderBy('pivot_updated_at', 'desc');
    }

    public function materials(){
        return $this->hasMany('App\Models\Material')->orderBy('updated_at', 'desc');
    }
}
