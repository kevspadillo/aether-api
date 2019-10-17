<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use JWTAuth;
use App\Models\ShareTransactions;
use App\Models\LoanTypes;

class MaxLoanableAmount implements Rule
{
    private $loanType; 
    private $loanableAmount; 

    function __construct($loanType)
    {

        $this->loanType = $loanType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        switch ($this->loanType) {
            case LoanTypes::EMERGENCY:
                $this->loanableAmount = 10000;
            break;
            case LoanTypes::REGULAR:
                $this->loanableAmount = 70000;
            break;
        }

        return  $value <= $this->loanableAmount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient Share Capital. The maximum loanable amount is: ' . number_format($this->loanableAmount, 2);
    }
}