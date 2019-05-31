<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;

class UpdateSupplier extends FormRequest
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
            'name_company' => "string|filled",
            'name_owner'=> "string|filled",
            'name_pic' => "string|filled",
            'name_sales' => "string|filled",
            'address' => "string|filled",
            'no_telp_company' => "string|filled",
            'no_telp_owner' => "string|filled",
            'email' => "email|filled|unique:suppliers,email",
            'fax' => "string|filled",
            'npwp' => "string|filled",
            'no_rek' => "string|filled",
            'pricelists.*.goods_id' => "required|integer|exists:goods,id",
            'pricelists.*.price' => "required|integer"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
