<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;

class StoreMaterialRequest extends FormRequest
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
            "code" => "required|string",
            "division_id" => "required|integer|exists:divisions,id",
            "request_by_user_id" => "required|integer|exists:users,id",
            "periode_id" => "required|integer|exists:periodes,id",
            "material_request_details.*.goods_id" => "required|integer|exists:divisions,id",
            "material_request_details.*.qty" => "required|integer|min:1",
            "material_request_details.*.notes" => "required|string",
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
