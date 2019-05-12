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
            'name' => "string",
            'code' => "string|unique:goods",
            'desc' => "string",
            'margin' => "integer",
            'value' => "integer",
            'status' => "integer",
            'last_buy_pricelist' => "integer",
            'barcode_master' => "string",
            "thumbnail" => "image|max:2048|mimes:jpeg,bmp,png,jpg",
            'avgprice_status' => "boolean",
            'tax' => "integer",
            'unit_id' => "integer|exists:units,id",
            'cogs_id' => "integer|exists:cogs,id",
            'attribute_goods.*.value'=> "required|integer|min:1",
            'attribute_goods.*.attribute_id'=> "required|integer|exists:attributes,id",
            'category_goods.*.category_id'=> "required|integer|exists:categories,id",
            'material_goods_update.*.id'=> "filled|required_with:material_goods_update.*.total,material_goods_update.*.adjust,material_goods_update.*.name|integer|exists:materials,id",
            'material_goods_update.*.total'=> "filled|integer|min:1",
            'material_goods_update.*.adjust'=> "nullable",
            'material_goods_update.*.name'=> "filled|string",
            'material_goods_new.*.total'=> "required_with:material_goods_new.*.name,material_goods_new.*.adjust|integer|min:1",
            'material_goods_new.*.adjust'=> "string|nullable",
            'material_goods_new.*.name'=> "required_with:material_goods_new.*.total,material_goods_new.*.adjust|string",
            'material_goods_delete.*.id'=> "filled|integer|exists:materials,id",
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
