<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\InvalidParameterException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRack extends FormRequest
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
            'warehouse_id' => "required|integer|min:1|exists:warehouses,id",
            
            'goods_racks.*.id' => "filled|integer|min:1|exists:goods_rack,id" ,
            'goods_racks.*.goods_id' => "filled|integer|min:1|exists:goods,id" ,
            'goods_racks.*.stock' => "filled|integer|min:1",
            'goods_racks.*.type'=> "required|in:1,0,-1",//1 : update, 0 : update, -1 : delete
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
