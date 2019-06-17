<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Http\Traits\Uuids;
use Storage;

class Goods extends Model
{
    use SoftDeletes,Uuids;

    protected $fillable = [
        'name','code','desc','margin','value','status','last_buy_pricelist','barcode_master','thumbnail','avg_price_status',"avg_price",'user_id','tax','unit_id','cogs_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'uuid'
    ];

    public function updatePriceSellings($priceSellings){
        foreach($priceSellings as $priceSelling){
            if($priceSelling['type'] == 1) {
                $this->priceSelling()->create($priceSelling);
            }
            else if($priceSelling['type'] == 0) {
                $this->priceSelling()->find($priceSelling['id'])->update($priceSelling);
            } else {
                $this->priceSelling()->find($priceSelling['id'])->delete();
            }
        }
    }

    public function updateMaterials($materials){
        foreach($materials as $material){
            if($material['type'] == 1) {
                $this->materials()->create($material);
            }
            else if($material['type'] == 0) {
                $this->materials()->find($material['id'])->update($material);
            } else {
                $this->materials()->find($material['id'])->delete();
            }
        }
    }

    public static function allDataCreate(){
        return ['categories' => Category::all(['id','name']), 'warehouses' => Warehouse::all(['id','name']), 'category_price_sellings' => CategoryPriceSelling::all(['id','name']),'attributes' => Attribute::all(['id','name']),'units'=>Unit::all(['id','name']),'cogs'=>Cogs::all(['id','name']),'suppliers'=>Supplier::all(['id','name_company','name_owner','name_pic','name_sales'])];
    }

    public function goodsRack(){
        return $this->hasMany('App\Models\GoodsRack');
    }
    
    public function pricelists(){
        return $this->hasMany('App\Models\Pricelist');
    }

    // public function suppliers(){
    //     return $this->belongsToMany('App\Models\Supplier','pricelists','goods_id','supplier_id')->withPivot('price')->withTimestamps()->orderBy('pivot_updated_at', 'desc');
    // }

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
    
    public function priceSelling(){
        return $this->hasMany('App\Models\PriceSelling')->orderBy('updated_at', 'desc');
    }

    public function stock(){
        return collect($this->goodsRack)->sum('stock');
    }

    public static function index(){
        $collectionGoods = Goods::latest()->get();

        $collectionGoods = $collectionGoods->map(function ($item) {
            $item = collect($item)->put('stock', $item->stock());
            Arr::set($item, 'thumbnail', Storage::url($item["thumbnail"]));
            
            return $item;
        });

        return $collectionGoods;
    }

    public function getRack(){
        $racks = $this->goodsRack->map(function ($item) {
            return ['id' => $item['id'], 'rack' => $item['rack']['name'], 'stock' => $item['stock']];
        });

        return $racks;
    }

    public function getSellingPrices(){
        $priceSellings = $this->priceSelling->map(function ($item) {
            $item = Arr::add($item, 'warehouse_name', $item['warehouse']['name']);
            $item = Arr::add($item, 'category_price_selling_name', $item['categoryPriceSelling']['name']);
            return Arr::except($item, ['warehouse','categoryPriceSelling']);
        });

        return $priceSellings;
    }

    public function getPricelist(){
        $pricelists = $this->suppliers->map(function ($item) {
            return ['id' => $item['id'], 'supplier' => $item['name_company'], 'price' => $item['pivot']['price']];
        });

        return $pricelists;
    }
    

}
