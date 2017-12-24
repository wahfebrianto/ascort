<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditSaleRequest extends Request
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
            'agent_id'                  =>  'required|integer',
            'product_id'                =>  'required',
            'number'                    =>  'required|string|min:1|max:50',
            'customer_id'               =>  'required|integer',
            'MGI'                       =>  'required',
            'currency'                  =>  'required',
            'MGI_start_date'            =>  'required|date_format:d/m/Y',
            'nominal'                   =>  'required|numeric',
            'interest'                  =>  'required|numeric',
            'branch_office_id'			=>	'required|integer',
            'tenor'						=>	'required',
            'bank'						=>	'required|string',
            'bank_branch'				=>	'required|string',
            'account_name'				=>	'required|string',
            'account_number'			=>	'required|string'
        ];
    }

    protected function getValidatorInstance(){
        $validator = parent::getValidatorInstance();

        $validator->sometimes('nominal', 'min:250000000', function($input)
        {
            $code = $input['nominal'] % 1000;
            if($code == config('sales_ccode.code')) return false; else return true;
        });
        return $validator;
    }
}
