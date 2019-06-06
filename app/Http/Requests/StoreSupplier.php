<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;

class StoreSupplier extends FormRequest
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
            'name_company' => "string|required",
            'name_owner'=> "string|required",
            'name_pic' => "string|required",
            'name_sales' => "string|required",
            'address' => "string|required",
            'no_telp_company' => "string|required",
            'no_telp_owner' => "string|required",
            'email' => "string|required|unique:suppliers,email",
            'fax' => "string|required",
            'npwp' => "string|required",
            'no_rek' => "string|required",
            'pricelists.*.goods_id' => "required|integer|exists:goods,id",
            'pricelists.*.price' => "required|integer",
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
