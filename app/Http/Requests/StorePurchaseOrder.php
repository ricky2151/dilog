<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\InvalidParameterException;
use Illuminate\Contracts\Validation\Validator;

class StorePurchaseOrder extends FormRequest
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
            'no_po' => "required|string|unique:purchase_orders,no_po",
            'supplier_id' => "required|integer|exists:suppliers,id",
            'payment_type' => "required|in:1,2", //1 untuk tempo, 2 untuk tunai
            'type' => "required|in:1,2,3", //1 untuk PO langsung, 2 untuk PO PR, 3 untuk PO min
            'payment_terms' => "nullable|required_if:payment_type,1|integer|min:1",
            'purchase_request_id' => "required_if:type,2|integer|exists:purchase_requests,id",
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
