<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use JWTAuth;
use App\Models\ShareTransactions;
use App\Models\LoanTypes;

class CheckCoMakers implements Rule
{

    private $loanType; 
    private $loanAmount; 
    private $loanableAmount; 
    private $individualRequiredShare; 

    function __construct($loanType, $loanAmount)
    {

        $this->loanType = $loanType;
        $this->loanAmount = $loanAmount;
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
        $isValidCoMakers = true;

        if (LoanTypes::REGULAR == $this->loanType) {        
            $user = JWTAuth::parseToken()->authenticate();
            
            $shareTotal = $this->ShareTransactions->getShareTransactionTotal($user->user_id);
            $this->loanableAmount = ($shareTotal->share * 2);
            
            $exeededAmount = $this->loanAmount - $this->loanableAmount;
            $this->individualRequiredShare = ($exeededAmount / count($value));

            foreach ($value as $coMakerId) {
                $coMakerShareCapital = $this->ShareTransactions->getShareTransactionTotal($coMakerId);

                if (empty($coMakerShareCapital)) {
                    $isValidCoMakers = false;
                    break;
                }

                if ($coMakerShareCapital->share < $this->individualRequiredShare) {
                    $isValidCoMakers = false;
                }
            }
        }
        return $isValidCoMakers;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Selected Co-Maker(s) does not have enough required Share Capital. Individual Required Share Capital: PHP ' . number_format($this->individualRequiredShare, 2);
    }
}