<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Http\Traits\Uuids;
use Storage;
use App\Exceptions\DatabaseTransactionErrorException;
use Illuminate\Support\Facades\DB;

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
                $this->priceSellings()->create($priceSelling);
            }
            else if($priceSelling['type'] == 0) {
                $this->priceSellings()->find($priceSelling['id'])->update($priceSelling);
            } else {
                $this->priceSellings()->find($priceSelling['id'])->delete();
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

    public function updatePricelists($pricelists){
        foreach($pricelists as $pricelist){
            if($pricelist['type'] == 1) {
                $this->pricelists()->create($pricelist);
            }
            else if($material['type'] == 0) {
                $this->pricelists()->find($pricelist['id'])->update($pricelist);
            } else {
                $this->pricelists()->find($pricelist['id'])->delete();
            }
        }
    }

    public function goodsRack(){
        return $this->hasMany('App\Models\GoodsRack');
    }
    
    public function pricelists(){
        return $this->hasMany('App\Models\Pricelist');
    }

    public function suppliers(){
        return $this->belongsToMany('App\Models\Supplier','pricelists','goods_id','supplier_id')->withPivot('price')->withTimestamps()->orderBy('pivot_updated_at', 'desc');
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
    
    public function priceSellings(){
        return $this->hasMany('App\Models\PriceSelling')->orderBy('updated_at', 'desc');
    }

    public function stock(){
        return collect($this->goodsRack)->sum('stock');
    }

    public function index(){
        $collectionGoods = $this->latest()->get();

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
        $priceSellings = $this->priceSellings->map(function ($item) {
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

    public function formatDataRelationAttributeGoods($data){
        $data['attribute_goods'] = $data['attribute_goods']->map(function($item){
            return ['attribute'=>['id'=> $item['id'], 'name'=> $item['name']], "value" => $item['pivot']['value']];
        });
        return $data['attribute_goods'];
    }

    public function formatDataRelationCategoryGoods($data){
        $data['category_goods'] = $data['category_goods']->map(function($item){
            return ['category'=>['id'=> $item['id'], 'name'=> $item['name']]];
        });
        return $data['category_goods'];
    }

    public function selectedColoumns($data, $coloumn, $selectedColoumns){
        $selectedColoumns = collect($selectedColoumns);
        return $data->map(function($item) use($selectedColoumns, $coloumn){
            $data = collect();
            foreach ($selectedColoumns as $selectedColoumn) {
                $data[Str::snake($selectedColoumn)] = $item["$selectedColoumn"];
            }
            if(is_null($coloumn)){
                return $data;
            }
            else{
                return ["$coloumn"=>$data];
            }
        });
    }

    public function jsonChangeKey($arr, $oldkey, $newkey) {
        $json = str_replace('"'.$oldkey.'":', '"'.$newkey.'":', json_encode($arr));
        return json_decode($json); 
       }

    public function getDataAndRelation($id){
        $data = $this->with('unit:id,name',
                    'cogs:id,name',
                    'priceSellings',
                    'priceSellings.warehouse:id,name',
                    'priceSellings.categoryPriceSelling:id,name',
                    'pricelists',
                    'pricelists.supplier:id,name_company',
                    'attributes',
                    'categories',
                    'materials'
                )->where('id',$id)->first();
        $data['attribute_goods'] = $data['attributes'];
        $data['category_goods'] = $data['categories'];
        
        //olah data untuk user
        $data['attribute_goods'] = $this->formatDataRelationAttributeGoods($data);
        $data['category_goods'] = $this->selectedColoumns($data['category_goods'],'category',["id","name"]);
        $data['price_sellings'] = $this->selectedColoumns($data['priceSellings'],null,["id","stock_cut_off","free","price","warehouse","categoryPriceSelling"]);
        $tampPricelists = $this->selectedColoumns($data['pricelists'],null,["id","price","supplier"]);
        $tampMaterials = $this->selectedColoumns($data['materials'],null,["id","name","total","adjust"]);

        $data = Arr::except($data, 
            ['attributes',
            'categories',
            'user_id',
            'unit_id',
            'cogs_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'priceSellings',
            'pricelists',
            'materials'
            ]);

        $data["thumbnail"] = Storage::url($data["thumbnail"]);
        $data["pricelists"] = $tampPricelists;
        $data["materials"] = $tampMaterials;

        return $data;
    }
    
    public function getMasterData(){
        return [
            'categories' => Category::all(['id','name']), 
            'warehouses' => Warehouse::all(['id','name']), 
            'category_price_sellings' => CategoryPriceSelling::all(['id','name']),
            'attributes' => Attribute::all(['id','name']),
            'units'=>Unit::all(['id','name']),
            'cogs'=>Cogs::all(['id','name']),
            'suppliers'=>Supplier::all(['id','name_company','name_owner','name_pic','name_sales'])
        ];
    }
}
