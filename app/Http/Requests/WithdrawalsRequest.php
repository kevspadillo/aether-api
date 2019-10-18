<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\CheckWithdrawalAmount;

class WithdrawalsRequest extends FormRequest
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
            'amount'              => ['required', 'numeric', 'min:1'],
            'amount_in_words'     => 'required',
            'check_number'        => 'numeric',
            'representative_name' => '',
            'payment_date'        => 'required|after:today'
        ];

        $rules['amount'][] = new CheckWithdrawalAmount();

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
            'amount.required'          => 'Amount is required.',
            'amount.numeric'           => 'Invalid amount.',
            'amount.min'               => 'Invalid amount.',
            'amount_in_words.required' => 'Amount in words is required.',
            'check_number.numeric'     => 'Invalid check number.',
            'payment_date.required'    => 'You must provide the payment date.',
            'payment_date.after'       => 'Selected payment date must not be the past dates.',
        ];
    }
}
