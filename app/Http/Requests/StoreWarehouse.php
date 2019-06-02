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
            "pic" => "required|string",
            "store_type" => "required|in:1, 2", // 1 : untuk copy rack+goods, 2 : untuk biasa
            "warehouse_id" => "required_if:store_type,1|integer|exists:warehouses,id",
            "copy_racks.*.id" => "required_if:store_type,1|integer|exists:racks,id",
            "copy_racks.*.is_with_goods" => "filled|boolean",
            "racks.*.name" => "required_if:store_type, 2|string"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
