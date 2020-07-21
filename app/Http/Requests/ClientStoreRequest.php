<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
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
            'email' => 'required|email|unique:users|max:190',
            'password'  =>  'required|min:8',
            'confirmpassword'   =>  'required|same:password',
            'user_first_name' =>  'required',
            'user_last_name'  =>  'required',
            //'user_mobile' => 'nullable|numeric',
            //'user_phone' => 'nullable|numeric',
            'user_profile_image' => 'nullable|mimes:jpeg,png,jpg,JPG,JPEG',

            'client_number' => 'required|alpha_num|unique:client_details',
            'client_company' => 'required',
            'client_work_designation' => 'required',
            'client_tax_register_number' => 'nullable|numeric|min:15',
            'client_tax_period' =>   'required',
            'client_first_tax_period_start' =>   'required',
            'client_first_tax_period_end' =>   'required',
            //'client_payment_date' =>  'required',
            'client_vat_certificate' => 'nullable|mimes:pdf',
            'client_trade_license' => 'nullable|mimes:pdf',
            
            //'contract_amount' => 'required|numeric',
            //'contract_start_date' => 'required',
            //'contract_end_date' => 'required',

            //'contract_services_id' => 'required'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    /*public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'name.required' => 'Name is required!',
            'password.required' => 'Password is required!'
        ];
    }*/
}
