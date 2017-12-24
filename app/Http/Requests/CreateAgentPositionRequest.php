<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateAgentPositionRequest extends Request
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
            'parent_id'     =>  'unique:agent_positions,parent_id',
            'acronym'     =>  'required|string|min:2|max:10',
            'level'     => 'required,unique:agent_positions,level'
        ];
    }
}
