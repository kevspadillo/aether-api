<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SavingsTransactions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'savings_transaction_id',
        'member_id',
        'member_transaction_id',
        'reference_id',
        'transaction_date',
        'savings_deposit',
        'interest',
        'savings',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'savings_transaction_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getSavingsTransactions($memberTransactionId)
    {
        $query = DB::table('savings_transactions');
        $query->select('savings_transactions.*');
        $query->join('users', 'users.user_id', '=', 'savings_transactions.member_id');
        $query->where('savings_transactions.member_transaction_id', '=', $memberTransactionId);
        return $query->get();
    }

    public function getMemberSavingsTransactions($memberId, $getPosted = true)
    {
        $query = DB::table('savings_transactions');

        $query->select(
            'savings_transactions.*',
            'member_transactions.*',
            DB::raw('CONCAT(users.firstname, " ", users.lastname) as posted_by')
        );
        $query->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'savings_transactions.member_transaction_id');
        $query->join('users', 'users.user_id', '=', 'member_transactions.user_id');
        $query->where('savings_transactions.member_id', '=', $memberId);

        if ($getPosted) {
            $query->where('member_transactions.is_posted', '=', 1);
        }

        $query->orderBy('savings_transactions.transaction_date', 'DESC');
        return $query->get();  
    }


    public function saveImportedSavingsTransactions($newSavings, $memberId)
    {
        $savingsTransactions = $this->getMemberSavingsTransactions($memberId, false);

        $currentTransactionDates = [];
        foreach ($savingsTransactions as $savingsTransaction) {
            $currentTransactionDates[] = $savingsTransaction->transaction_date;
        }

        $newSavingsTransactions = [];
        foreach ($newSavings as $transactionDate => $savings) {

            $date = explode('|', $transactionDate);

            if (!in_array($date[1], $currentTransactionDates)) {
                $newSavingsTransactions[] = $savings;
            }    
        }

        return self::insert($newSavingsTransactions);
    }
}
