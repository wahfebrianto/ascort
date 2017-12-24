<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Product;

class EditProductRequest extends Request
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
            'product_code'      =>  'required|string|max:20',
            'product_name'      =>  'required|string|min:2|max:150'
        ];
    }

    protected function getValidatorInstance(){
        $validator = parent::getValidatorInstance();

        $validator->sometimes('product_code', 'unique:product,product_code', function($input)
        {
            $id = $input->get('id');
            $new_code = $input->get('product_code');
            $agent = Product::find($id);
            $old_code = $agent->product_code;
            return $new_code != $old_code;
        });

        return $validator;
    }
}
