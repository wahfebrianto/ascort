<?php

namespace App\Http\Requests;

use App\Agent;
use App\Http\Requests\Request;

use Illuminate\Validation\Factory;

class EditAgentRequest extends Request
{
    public function __construct(Factory $factory)
    {
        $factory->extend('parent', function ($attribute, $value, $parameters)
        {
            /* if($value != 'none') {
                $agent_position_id = \Input::get('agent_position_id');
                $agent_position = \App\AgentPosition::where('id', '=', $agent_position_id)->get()->first();
                $parent = \App\Agent::where('id', '=', $value)->with('agent_position')->get()->first();
                $minus = $parent->agentPositionLevel - $agent_position->level;
                return $minus == 1 || ($parent->agentPositionLevel == 1 && $agent_position->level == 1);
            } */
            return true;
        },
            'Agent position must be one level below its parent.'
        );
    }
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
            'NIK'                   =>  'required|digits:16',
            'name'                  =>  'required|string|min:3|max:200',
            'birth_place'           =>  'required|string|max:50',
            'DOB'                   =>  'required|date_format:d/m/Y',
            'gender'                =>  'required|string|size:1',
            'address'               =>  'required|string|min:5',
            'phone_number'          =>  'required|string|digits_between:5,15',
            'handphone_number'      =>  'required|string|digits_between:7,15',
            'email'                 =>  'required|string|min:5|email',
            'state'                 =>  'required|string',
            'city'                  =>  'required|string',
            'zipcode'               =>  'required|string|digits:5',
            'agent_position_id'     =>  'exists:agent_positions,id',
            'join_date'             =>  'required',
            'NPWP'                  =>  'required|numeric',
            'bank'                  =>  'required|string|max:150',
            'bank_branch'           =>  'required|string|max:150',
            'account_number'        =>  'required|string|max:50',
            'account_name'          =>  'required|string|min:3|max:200',
            'parent_id'             =>  'required|parent'
        ];
    }

    protected function getValidatorInstance(){
        $validator = parent::getValidatorInstance();

        $validator->sometimes('NIK', 'unique:agents,NIK', function($input)
        {
            $id = $input->get('id');
            $new_NIK = $input->get('NIK');
            $agent = Agent::find($id);
            $old_NIK = $agent->NIK;
            return $new_NIK != $old_NIK;
        });

        $validator->sometimes('NPWP', 'digits:15', function($input)
        {
            if($input['NPWP'] == "0") return false; else return true;
        });

        return $validator;
    }
}
