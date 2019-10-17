<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MemberTransactions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_transaction_id',
        'transaction_type',
        'transaction_date',
        'is_posted',
        'user_id',
        'create_datetime',
        'posted_datetime',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'member_transaction_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getMemberTransactions($transactionType)
    {
        $query = DB::table('member_transactions');
        $query->select(
                'member_transactions.*',
                DB::raw('CONCAT(poster.firstname, " ", poster.lastname) as posted_by'),
                DB::raw('CONCAT(users.firstname, " ", users.lastname) as uploaded_by'),
            );
        $query->join('users', 'users.user_id', '=', 'member_transactions.user_id');
        $query->leftJoin('users as poster', 'poster.user_id', '=', 'member_transactions.posted_by_user_id');
        $query->where('member_transactions.transaction_type', '=', $transactionType);
        $query->orderBy('member_transactions.created_datetime', 'DESC');
        return $query->get();
    }

    public function contributions()
    {
        return $this->hasMany('App\Models\LoanTransactions', 'member_transaction_id', 'member_transaction_id');
    }
}
