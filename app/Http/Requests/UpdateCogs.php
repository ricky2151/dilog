<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException; 
use Illuminate\Foundation\Http\FormRequest;

class UpdateCogs extends FormRequest
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
            // 'cogs_component.*.id' => "filled|integer|exists:cogs_components,id",
            // 'cogs_component.*.name' => "filled|string",
            // 'cogs_component.*.value' => "filled|integer|min:0",
            // 'cogs_component.*.info' => "filled|string",
            // 'cogs_component.*.type'=> "required|in:1,0,-1",//1 : update, 0 : update, -1 : delete
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
