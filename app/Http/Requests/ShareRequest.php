<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShareRequest extends FormRequest
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
            'number_of_shares' => 'required|numeric|min:1',
            'payment_date'     => 'required|after:today'
        ];

        switch($this->method()) {
            case 'POST' : break;
            case 'PUT'  :
            case 'PATCH':
                $rules['status_id'] = 'required|exists:status_lookup,status_lookup_id';
                break;
            default: break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'number_of_shares.required'=> 'Number of shares is required.',
            'number_of_shares.numeric' => 'Invalid value for number of shares.',
            'payment_date.required'    => 'You must provide the payment date.',
            'number_of_shares.min'     => 'Minimum of 1 share (PHP 50.00 ) value is required.',
            'payment_date.after'       => 'Selected payment date must not be the past dates.',
            'status_id.exists'         => 'Share status is not valid.'
        ];
    }
}
