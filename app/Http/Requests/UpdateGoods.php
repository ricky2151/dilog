<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGoods extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "filled|string",
            'code' => "filled|string|unique:goods",
            'desc' => "filled|string",
            'margin' => "filled|integer",
            'value' => "filled|integer",
            'status' => "filled|integer",
            'last_buy_pricelist' => "filled|integer",
            'barcode_master' => "filled|string",
            "thumbnail" => "image|max:2048|mimes:jpeg,bmp,png,jpg",
            'avg_price_status' => "filled|boolean",
            'avg_price' => "filled|integer|min:0",
            'tax' => "filled|integer",
            'unit_id' => "filled|integer|exists:units,id",
            'cogs_id' => "filled|integer|exists:cogs,id",
            
            'attribute_goods.*.value'=> "required|integer|min:1",
            'attribute_goods.*.attribute_id'=> "required|integer|exists:attributes,id",
            
            'category_goods.*.category_id'=> "required|integer|exists:categories,id",
            
            'material_goods.*.id' => "filled|integer|exists:materials,id",
            'material_goods.*.total'=> "filled|integer|min:1",
            'material_goods.*.adjust'=> "string|nullable",
            'material_goods.*.name'=> "filled|string",
            'material_goods.*.type'=> "required|in:1,0,-1",//1 : update, 0 : update, -1 : delete

            'pricelists.*.supplier_id' => "required|integer|exists:suppliers,id",
            'pricelists.*.price' => "required|integer",

            'price_sellings.*.id' => "required|integer|exists:price_sellings,id",
            'price_sellings.*.warehouse_id' => "required|integer|exists:warehouses,id",
            'price_sellings.*.stock_cut_off' => "required|integer|min:0",
            'price_sellings.*.category_price_selling_id' => "required|integer|exists:category_price_sellings,id",
            'price_sellings.*.price' => "required|integer|min:0",
            'price_sellings.*.free' => "required|boolean",
            'price_sellings.*.type'=> "required|in:1,0,-1",//1 : update, 0 : update, -1 : delete

            
            
            'is_image_delete' => "required|boolean"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
