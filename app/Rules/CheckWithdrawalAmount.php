<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use JWTAuth;
use App\Models\ShareTransactions;
use App\Models\LoanTypes;
use App\Models\SavingsTransactions;

class CheckWithdrawalAmount implements Rule
{
    private $currentSavingsDeposit; 
    private $SavingsTransactions; 

    public function __construct()
    {
        $this->SavingsTransactions = new SavingsTransactions();
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
        $user = JWTAuth::parseToken()->authenticate();

        $savings = $this->SavingsTransactions->getShareTransactionTotal($user->user_id);
        
        if (!$savings) {
            return false;
        }

        $this->currentSavingsDeposit = $savings->savings;
        return  $value < $this->currentSavingsDeposit;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient Savings Deposit. Your current savings deposit is PHP: ' . number_format($this->currentSavingsDeposit, 2);
    }
}