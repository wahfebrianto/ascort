<?php

namespace App\Http\Requests;

use App\Customer;
use App\Http\Requests\Request;

class EditCustomerRequest extends Request
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
          'NIK'                   =>  'required|digits:16',
		  'NPWP'                  =>  'required|numeric',
          'name'                  =>  'required|string|min:3|max:200',
          'gender'                =>  'required|string|size:1',
          'address'               =>  'required|string|min:5',
          'phone_number'          =>  'required|string|digits_between:5,15',
          'handphone_number'      =>  'required|string|digits_between:7,15',
          'email'                 =>  'required|string|min:5|email',
          'state'                 =>  'required|string',
          'city'                  =>  'required|string',
          'zipcode'               =>  'required|string|digits:5',
          'cor_address'               =>  'required|string|min:5',
          'cor_state'                 =>  'required|string',
          'cor_city'                  =>  'required|string',
          'cor_zipcode'               =>  'required|string|digits:5',
        ];
    }



    protected function getValidatorInstance(){
        $validator = parent::getValidatorInstance();

        $validator->sometimes('NIK', 'unique:customers,NIK', function($input)
        {
            $id = $input->get('id');
            $new_NIK = $input->get('NIK');
            $customer = Customer::find($id);
            $old_NIK = $customer->NIK;
            return $new_NIK != $old_NIK;
        });

		$validator->sometimes('NPWP', 'digits:15', function($input)
        {
            if($input['NPWP'] == "0") return false; else return true;
        });
		
        return $validator;
    }
}
