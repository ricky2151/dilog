<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsRack extends FormRequest
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
            'goods_id' => 'required|integer|exists:goods,id',
            'rack_id' => 'required|integer|exists:racks,id',
            'stock' => 'required|integer|min:0',
            'price_selling.*.stock_cut_off' => 'required|integer',
            'price_selling.*.category_price_selling_id' => 'required|integer|exists:category_price_sellings,id',
            'price_selling.*.price' => 'required|integer|min:0',
            'price_selling.*.discount' => 'nullable|integer',
            'price_selling.*.free' => 'required|boolean',
            'batch.*.stock' => 'required|integer|min:0',
            'batch.*.batch_number' => 'required|string',
            'batch.*.source_id' => 'required|integer|exists:sources,id'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
