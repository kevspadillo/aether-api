<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateLoanRequest extends FormRequest
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
            'loan_amount'   => 'required|numeric|min:1',
            'months_to_pay' => 'required|numeric|min:1|max:36',
        ];

        switch($this->method()) {
            case 'POST' : break;
            case 'PUT'  :
            case 'PATCH':
                // $rules['status_id'] = 'required|exists:status_lookup,status_lookup_id';
                break;
            default: break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'loan_amount.required'   => 'Amount is required.',
            'loan_amount.numeric'    => 'Invalid amount.',
            'loan_amount.min'        => 'Invalid amount.',
            'months_to_pay.required' => 'Amount is required.',
            'months_to_pay.numeric'  => 'Invalid amount.',
            'months_to_pay.min'      => 'Minimum of 1 month.',
            'months_to_pay.max'      => 'Maximum of 36 months.',
        ];
    }
}
