<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException; 
use Illuminate\Foundation\Http\FormRequest;

class StoreCogs extends FormRequest
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
            'name' => "required|string",
            'nominal' =>"required|integer|min:0",
            'type_id' =>"required|integer|min:1|exists:types,id",
            'cogs_component.*.name' => "required|string",
            'cogs_component.*.value' => "required|integer|min:0",
            'cogs_component.*.info' => "required|string"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
