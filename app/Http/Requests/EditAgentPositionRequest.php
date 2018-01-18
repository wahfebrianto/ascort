<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditAgentPositionRequest extends Request
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
            'name'     =>  'required|string|min:2|max:100',
            'acronym'     =>  'required|string|min:2|max:10',
            'level'     => 'required'
        ];
    }
}
