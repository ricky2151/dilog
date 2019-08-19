<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;

class StorePurchaseRequestDetail extends FormRequest
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
            'purchase_request_details.*.goods_id' => "integer|required|exists:goods,id",
            'purchase_request_details.*.qty' => "string|required",
            'purchase_request_details.*.price' => "integer|required|min:1",
            'purchase_request_details.*.supplier_id' => "integer|required|exists:suppliers,id",
            'purchase_request_details.*.pricelist_id' => "integer|required|exists:pricelists,id",
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
