<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use JWTAuth;
use App\Models\ShareTransactions;
use App\Models\LoanTypes;
use App\Models\Loan;
use App\User;
use DateTime;

class ValidateLoanApproval implements Rule
{
    private $loanId;
    private $validationMessage;
    private $ShareTransactions;

    function __construct($loanId)
    {
        $this->loanId = $loanId;
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
        $loan = Loan::find($this->loanId);
        $user = User::find($loan->user_id);

        $currentDate            = new DateTime();
        $memberRegistrationDate = new DateTime($user->created_datetime);

        $registedMonths = $currentDate->diff($memberRegistrationDate)->m;

        if (6 > $registedMonths) {
            $this->validationMessage = 'Unable to Approve Loan, Member is registered for less than 6 months. ';
            return false;
        }

        $share = $this->ShareTransactions->getShareTransactionTotal($user->user_id);

        if (5000 > $share->share) {
            $this->validationMessage = 'Insufficient Share Capital. Minimum share capital is PHP 5,0000';
            return false;
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
        return $this->validationMessage;
    }
}