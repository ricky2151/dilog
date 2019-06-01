<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;

class UpdateMaterialRequest extends FormRequest
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
            "status" => "required|boolean",
            "periode_id" => "required|integer|exists:periodes,id",
            // "material_request_details.*.id" => "required|integer|exists:divisions,id",
            // "material_request_details.*.goods_id" => "required|integer|exists:divisions,id",
            // "material_request_details.*.qty" => "",
            // "material_request_details.*.status" => "",
            // "material_request_details.*.notes" => "",
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
