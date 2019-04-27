<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidRequestParameter; 
use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
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
            'email' => 'required|email|string|unique:users,email',
            'password' => 'required|min:8|string',
            'role_id' => 'required|integer|min:1|exists:roles,id',
            'warehouse_id' => "required|integer|min:1",
            'job_title' => "required|string"
        ];
    }
    
    protected function failedValidation(Validator $validator) {
        throw new InvalidRequestParameter($validator->errors()); 
    }
}
