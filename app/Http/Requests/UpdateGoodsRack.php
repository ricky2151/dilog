<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGoodsRack extends FormRequest
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
            'goods_id' => 'filled|integer|exists:goods,id',
            'rack_id' => 'filled|integer|exists:racks,id',
            'stock' => 'filled|integer|min:0',

            'price_sellings.*.id' => 'filled|integer|exists:price_sellings,id',
            'price_sellings_update.*.stock_cut_off' => 'required|integer',
            'price_sellings_update.*.category_price_selling_id' => 'required|integer|exists:category_price_sellings,id',
            'price_sellings_update.*.price' => 'required|integer|min:0',
            'price_sellings_update.*.discount' => 'nullable|integer',
            'price_sellings_update.*.free' => 'required|boolean',

            'price_sellings_new.*.stock_cut_off' => 'required_with:price_selling_new.*.price, price_selling_new.*.discount, price_selling_new.*.free, price_selling_new.*.category_price_selling_id|integer',
            'price_sellings_new.*.category_price_selling_id' => 'required_with:price_selling_new.*.price, price_selling_new.*.discount, price_selling_new.*.free, price_selling_new.*.stock_cut_off|integer|exists:category_price_sellings,id',
            'price_sellings_new.*.price' => 'required_with:price_selling_new.*.category_price_selling_id, price_selling_new.*.discount, price_selling_new.*.free, price_selling_new.*.stock_cut_off|integer|min:0',
            'price_sellings_new.*.discount' => 'nullable|integer',
            'price_sellings_new.*.free' => 'required_with:price_selling_new.*.category_price_selling_id, price_selling_new.*.discount, price_selling_new.*.price, price_selling_new.*.stock_cut_off|boolean',
            
            'price_sellings_update.*.id' => 'required_with:price_selling_update.*.stock_cut_off, price_selling_update.*.category_price_selling_id, price_selling_update.*.price, price_selling_update.*.discount, price_selling_update.*.free|integer|exists:price_sellings,id',
            'price_sellings_update.*.stock_cut_off' => 'required_with:price_selling_update.*.id, price_selling_update.*.category_price_selling_id, price_selling_update.*.price, price_selling_update.*.discount, price_selling_update.*.free|integer',
            'price_sellings_update.*.category_price_selling_id' => 'required_with:price_selling_update.*.id, price_selling_update.*.stock_cut_off, price_selling_update.*.price, price_selling_update.*.discount, price_selling_update.*.free|integer|exists:category_price_sellings,id',
            'price_sellings_update.*.price' => 'required_with:price_selling_update.*.id, price_selling_update.*.stock_cut_off, price_selling_update.*.category_price_selling_id, price_selling_update.*.discount, price_selling_update.*.free|integer|min:0',
            'price_sellings_update.*.discount' => 'nullable|integer',
            'price_sellings_update.*.free' => 'required_with:price_selling_update.*.id, price_selling_update.*.stock_cut_off, price_selling_update.*.category_price_selling_id, price_selling_update.*.price, price_selling_update.*.discount|boolean',

            'price_sellings_delete.*.id' => "filled|integer|exists:price_sellings,id",
            
            'batch.*.stock' => 'required|integer|min:0',
            'batch.*.batch_number' => 'required|string',
            'batch.*.source_id' => 'required|integer|exists:sources,id'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
