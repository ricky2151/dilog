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
            'name' => "string|filled",
            'warehouse_id' => "filled|integer|min:1|exists:warehouses,id",
            'goods_racks_delete.*.id' => "required|integer|min:1|exists:goods_rack,id",
            'goods_racks_new.*.goods_id' => "required|integer|min:1|exists:goods,id" ,
            'goods_racks_new.*.stock' => "required|integer|min:1",
            'goods_racks_update.*.id' => "required|integer|min:1|exists:goods_rack,id" ,
            'goods_racks_update.*.goods_id' => "required|integer|min:1|exists:goods,id" ,
            'goods_racks_update.*.stock' => "required|integer|min:1",
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new InvalidParameterException($validator->errors()); 
    }
}
