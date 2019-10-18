<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory as ValidationFactory;
use App\Rules\CompareToShareCapital;
use App\Rules\MaxLoanableAmount;
use App\Rules\CheckCoMakers;

class LoanRequest extends FormRequest
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
        $data = $this->all();

        $rules = [
            'loan_amount'       => ['required'],
            'payment_date'      => 'required|after:today',
            'payment_method_id' => 'required',
            'loan_purpose_id'   => 'required',
            'loan_type'         => 'required',
            'co_makers'         => [],
        ];

        $rules['loan_amount'][] = new MaxLoanableAmount($data['loan_type']);

        if (!empty($data['co_makers'])) {
            $rules['co_makers'][]   = new CheckCoMakers($data['loan_type'], $data['loan_amount']);
        } else {
            $rules['loan_amount'][] = new CompareToShareCapital($data['loan_type']);
        }


        return $rules;
    }

    public function messages()
    {
        return [
            'loan_amount.required'       => 'Loan amount is required.',
            'payment_date.required'      => 'You must provide the payment date.',
            'payment_date.after'         => 'Selected payment date must not be the past dates.',
            'payment_method_id.required' => 'Payment method is required.',
        ];
    }
}
