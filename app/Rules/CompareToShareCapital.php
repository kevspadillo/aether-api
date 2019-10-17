<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use JWTAuth;
use App\Models\ShareTransactions;
use App\Models\LoanTypes;

class CompareToShareCapital implements Rule
{

    private $loanType; 
    private $loanableAmount; 

    function __construct($loanType)
    {

        $this->loanType = $loanType;
        $this->ShareTransactions = new ShareTransactions();
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
        if ($this->loanType == LoanTypes::REGULAR) {
            $user = JWTAuth::parseToken()->authenticate();
            $shareTotal = $this->ShareTransactions->getShareTransactionTotal($user->user_id);

            $this->loanableAmount = ($shareTotal->share * 2);
            return  $this->loanableAmount > $value;
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient Share Capital. Your loanable amount based from Share Capital is: ' . $this->loanableAmount . '. You might need a co-maker to submit this loan application.';
    }
}