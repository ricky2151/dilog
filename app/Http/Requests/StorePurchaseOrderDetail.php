<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\InvalidParameterException;
use Illuminate\Contracts\Validation\Validator;

class StorePurchaseOrderDetail extends FormRequest
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
            'purchase_order_id' => "required|integer|exists:purchase_orders,id",
            'goods_id' => 'required|integer|exists:goods,id',
            'pricelist_id' => 'required|integer|exists:pricelists,id',
            'qty' => 'required|integer|min:1',
            'tax' => 'required|integer|min:0',
            'discount_percent' => 'required_if:discount_choose,1|numeric|min:0',
            'discount_rupiah' => 'required_if:discount_choose,2|numeric|min:0',
            'discount_choose' => 'required|integer|in:1,2', //1 if user choose discount percent, 2 user choose discount rupiah
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
