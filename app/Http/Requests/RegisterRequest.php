<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $rules = [
            'firstname'        => 'required',
            'middlename'       => 'required',
            'lastname'         => 'required',
            'email'            => 'required|unique:users',
            'password'         => 'required|confirmed|min:8'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'firstname.required'  => 'First name is required.',
            'middlename.required' => 'Middle name is required.',
            'lastname.required'   => 'Last name is required.',
            'email.required'      => 'Email is required.',
            'password.required'   => 'Password is required.',
            'email.unique'        => 'Email already exists.',
            'password.confirmed'  => 'Passwords did not matched.',
            'password.min'        => 'Minimum of 8 characters for the password.'
        ];
    }
}
