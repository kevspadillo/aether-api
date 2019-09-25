<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ShareTransactions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_transaction_id',
        'member_id',
        'transaction_date',
        'reference_id',
        'share_capital',
        'share',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'share_transaction_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getShareTransactions($memberTransactionId)
    {
        $query = DB::table('share_transactions');
        $query->select('share_transactions.*');
        $query->join('users', 'users.user_id', '=', 'share_transactions.member_id');
        $query->where('share_transactions.member_transaction_id', '=', $memberTransactionId);
        return $query->get();
    }

    public function getMemberShareTransactions($memberId, $getPosted = true)
    {
        $query = DB::table('share_transactions');

        $query->select(
            'share_transactions.*',
            'member_transactions.*',
            DB::raw('CONCAT(users.firstname, " ", users.lastname) as posted_by')
        );
        $query->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'share_transactions.member_transaction_id');
        $query->join('users', 'users.user_id', '=', 'member_transactions.user_id');
        $query->where('share_transactions.member_id', '=', $memberId);

        if ($getPosted) {
            $query->where('member_transactions.is_posted', '=', 1);
        }

        $query->orderBy('share_transactions.transaction_date', 'DESC');
        return $query->get();        
    }


    public function saveImportedShareTransactions($newShares, $memberId)
    {
        $shareTransactions = $this->getMemberShareTransactions($memberId, false);

        $currentTransactionDates = [];
        foreach ($shareTransactions as $shareTransaction) {
            $currentTransactionDates[] = $shareTransaction->transaction_date;
        }

        $newShareTransactions = [];
        foreach ($newShares as $transactionDate => $share) {

            $date = explode('|', $transactionDate);

            if (!in_array($date[1], $currentTransactionDates)) {
                $newShareTransactions[] = $share;
            }    
        }

        return self::insert($newShareTransactions);
    }

    public function getShareTransactionTotal($memberId)
    {
        $query = DB::table('share_transactions');
        $query->select('share_transactions.share');
        $query->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'share_transactions.member_transaction_id');
        $query->join('users', 'users.user_id', '=', 'member_transactions.user_id');
        $query->where('share_transactions.member_id', '=', $memberId);
        $query->where('member_transactions.is_posted', '=', 1);
        $query->orderBy('share_transactions.transaction_date', 'DESC');
        $query->limit(1);
        return $query->first();  
    }

    public function getYearToDateShareTransactionTotal($memberId)
    {
        $query = DB::table('share_transactions');
        $query->select(DB::raw('SUM(share_transactions.share_capital) as share_capital'));
        $query->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'share_transactions.member_transaction_id');
        $query->join('users', 'users.user_id', '=', 'member_transactions.user_id');
        $query->where('share_transactions.member_id', '=', $memberId);
        $query->where('member_transactions.is_posted', '=', 1);
        $query->where('share_transactions.transaction_date', '>=', date('Y-01-01'));
        $query->limit(1);

        return $query->first();  
    }
}
