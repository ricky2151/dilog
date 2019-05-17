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
            'name' => "string",
            'nominal' =>"integer",
            'type_id' =>"integer|min:1|exists:types,id",
            'cogs_components_new.*.name' => "required_with:cogs_component_new.*.value,cogs_component_new.*.info|string",
            'cogs_components_new.*.value' => "required_with:cogs_component_new.*.name,cogs_component_new.*.info|integer|min:0",
            'cogs_components_new.*.info' => "required_with:cogs_component_new.*.name,cogs_component_new.*.value|string",
            'cogs_components_update.*.id' => "required_with:cogs_component_update.*.name, cogs_component_update.*.value, cogs_component_update.*.info|integer|exists:cogs_components,id",
            'cogs_components_update.*.name' => "required_with:cogs_component_update.*.id, cogs_component_update.*.value, cogs_component_update.*.info|string",
            'cogs_components_update.*.value' => "required_with:cogs_component_update.*.id, cogs_component_update.*.name, cogs_component_update.*.info|integer|min:0",
            'cogs_components_update.*.info' => "required_with:cogs_component_update.*.id, cogs_component_update.*.name, cogs_component_update.*.value|string",
            'cogs_components_delete.*.id' => "filled|integer|exists:cogs_components,id"
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
