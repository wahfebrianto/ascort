<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditBranchOfficeRequest extends Request
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
            'branch_name'      =>  'required|string|min:3|max:200',
            'address'      =>  'required|string|min:5',
            'city'      =>  'required|string',
            'state'      =>  'required|string',
            'phone_number'      =>  'required|string|digits_between:5,15'
        ];
    }

}
