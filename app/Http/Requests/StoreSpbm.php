<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;

class StoreSpbm extends FormRequest
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
            'purchase_order_id' => 'required|integer|exists:purchase_orders,id', 
            'delivery_order_no' => 'required|string|unique:spbms,delivery_order_no', 
            'catatan' => 'nullable|string', 
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'spbm_details.*.goods_id' => 'required|integer|exists:goods,id',
            'spbm_details.*.qty' => 'required|integer|min:0',
            'spbm_details.*.notes' => 'nullable|string',
            'spbm_details.*.defective_qty' => 'nullable|integer|min:0',
            'spbm_details.*.rack_id' => 'required|integer|exists:racks,id',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
