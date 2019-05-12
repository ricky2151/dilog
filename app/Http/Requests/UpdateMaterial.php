<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterial extends FormRequest
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
            'name' => "string",
            'goods_id' => "integer|min:1|exists:types,id",
            'total' => "min:1|integer",
            'adjust' => "string"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
