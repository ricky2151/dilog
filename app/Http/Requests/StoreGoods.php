<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class StoreGoods extends FormRequest
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
            'name' => "required|string",
            'code' => "required|string|unique:goods",
            'desc' => "string|nullable",
            'margin' => "required|integer",
            'value' => "required|integer",
            'status' => "required|integer",
            'last_buy_pricelist' => "integer|nullable",
            'barcode_master' => "string|nullable",
            "thumbnail" => "nullable|image|max:2048|mimes:jpeg,bmp,png,jpg",
            'avgprice_status' => "required|boolean",
            'tax' => "integer|nullable",
            'unit_id' => "required|integer|exists:units,id",
            'cogs_id' => "required|integer|exists:cogs,id",
            'attribute_goods.*.value'=> "required|integer|min:1",
            'attribute_goods.*.attribute_id'=> "required|integer|exists:attributes,id",
            'category_goods.*.category_id'=> "required|integer|exists:categories,id",
            'material_goods.*.total'=> "required|integer|min:1",
            'material_goods.*.adjust'=> "string|nullable",
            'material_goods.*.name'=> "required|string",
            'pricelists.*.supplier_id' => "required|integer|exists:suppliers,id",
            'pricelists.*.price' => "required|integer"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
