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
            'avgprice_status' => "filled|boolean",
            'tax' => "filled|integer",
            'unit_id' => "filled|integer|exists:units,id",
            'cogs_id' => "filled|integer|exists:cogs,id",
            
            'attribute_goods.*.value'=> "required|integer|min:1",
            'attribute_goods.*.attribute_id'=> "required|integer|exists:attributes,id",
            
            'category_goods.*.category_id'=> "required|integer|exists:categories,id",
            
            'material_goods.*.id' => "filled|integer|exists:materials,id",
            'material_goods.*.total'=> "required|integer|min:1",
            'material_goods.*.adjust'=> "string|nullable",
            'material_goods.*.name'=> "required|string",
            'material_goods.*.type'=> "required|enum:1,0,-1",
            
            'is_image_delete' => "required|boolean"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
