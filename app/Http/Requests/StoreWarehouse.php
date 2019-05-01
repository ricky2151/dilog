<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouse extends FormRequest
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
            "name" => "required|string",
            "address" => "required|string",
            "lat" => "required|string",
            "lng" => "required|string",
            "telp" => "required|string",
            "email" => "required|string|email|unique:warehouses,email",
            "pic" => "required|image|max:2048|mimes:jpeg,bmp,png,jpg" 
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
