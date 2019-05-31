<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGoodsRack extends FormRequest
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
            'goods_id' => 'filled|integer|exists:goods,id',
            'rack_id' => 'filled|integer|exists:racks,id',
            'stock' => 'filled|integer|min:0',
            
            // 'batch.*.stock' => 'required|integer|min:0',
            // 'batch.*.batch_number' => 'required|string',
            // 'batch.*.source_id' => 'required|integer|exists:sources,id'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
