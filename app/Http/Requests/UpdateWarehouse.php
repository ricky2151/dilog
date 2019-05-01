<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouse extends FormRequest
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
            "name" => "string",
            "address" => "string",
            "lat" => "string",
            "lng" => "string",
            "telp" => "string",
            "email" => "string|email|unique:warehouses,email",
            "pic" => "image|max:2048|mimes:jpeg,bmp,png,jpg" 
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
