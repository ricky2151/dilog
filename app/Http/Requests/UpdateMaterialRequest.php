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
            "material_request_details.*.id" => "filled|integer|exists:material_request_details,id",
            "material_request_details.*.goods_id" => "filled|integer|exists:goods,id",
            "material_request_details.*.qty" => "filled|integer|min:1",
            "material_request_details.*.notes" => "filled|string",
            'material_request_details.*.type'=> "required|in:1,0,-1" //1 : new, 0 : update, -1 : delete
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
