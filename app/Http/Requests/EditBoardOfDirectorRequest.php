<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditBoardOfDirectorRequest extends Request
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
            'bod_name'      =>  'required|string|min:3|max:200',
            'city'      =>  'required|string',
            'NPWP' => 'required',
            'identity_number' => 'required'
        ];
    }

}
