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
            "name" => "filled|string",
            "address" => "filled|string",
            "lat" => "filled|string",
            "lng" => "filled|string",
            "telp" => "filled|string",
            "email" => "string|email|unique:warehouses,email",
            "pic" => "filled|string",
            "racks_update.*.id" => "required_with:racks_update.*.name|filled|integer|exists:racks,id",
            "racks_update.*.name" => "required_with:racks_update.*.id|filled|string",
            "racks_delete.*.id" => "filled|integer|exists:racks,id",
            "racks_new.*.name" => "filled|string"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
