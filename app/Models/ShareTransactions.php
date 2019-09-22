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
}
