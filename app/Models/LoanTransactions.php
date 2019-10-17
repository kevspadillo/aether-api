<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class LoanTransactions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_transaction_id',
        'member_id',
        'member_transaction_id',
        'reference_id',
        'transaction_date',
        'loans_receivable',
        'interest_on_loan',
        'penalty',
        'remaining_loan',
        'total_interest',
        'total_penalty',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'loan_transaction_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getLoanTransactions($memberTransactionId)
    {
        $query = DB::table('loan_transactions');
        $query->select('loan_transactions.*');
        $query->join('users', 'users.user_id', '=', 'loan_transactions.member_id');
        $query->where('loan_transactions.member_transaction_id', '=', $memberTransactionId);
        return $query->get();
    }

    public function getMemberLoanTransactions($memberId, $getPosted = true)
    {
        $query = DB::table('loan_transactions');

        $query->select(
            'loan_transactions.*',
            'member_transactions.*',
            DB::raw('CONCAT(users.firstname, " ", users.lastname) as posted_by')
        );
        $query->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'loan_transactions.member_transaction_id');
        $query->join('users', 'users.user_id', '=', 'member_transactions.user_id');
        $query->where('loan_transactions.member_id', '=', $memberId);

        if ($getPosted) {
            $query->where('member_transactions.is_posted', '=', 1);
        }

        $query->orderBy('loan_transactions.transaction_date', 'DESC');
        return $query->get();  
    }

    public function saveImportedLoanTransactions($newLoans, $memberId)
    {
        $loanTransactions = $this->getMemberLoanTransactions($memberId, false);

        $currentTransactionDates = [];
        foreach ($loanTransactions as $loanTransaction) {
            $currentTransactionDates[] = $loanTransaction->transaction_date;
        }

        $newLoansTransactions = [];
        foreach ($newLoans as $transactionDate => $savings) {

            $date = explode('|', $transactionDate);

            if (!in_array($date[1], $currentTransactionDates)) {
                $newLoansTransactions[] = $savings;
            }    
        }

        return self::insert($newLoansTransactions);
    }
}
