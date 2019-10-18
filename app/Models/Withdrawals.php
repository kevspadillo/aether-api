<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Withdrawals extends Model
{
    protected $notFoundMessage = 'The book could not be found';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'withdrawal_id',
        'user_id',
        'amount',
        'amount_in_words',
        'check_number',
        'representative_name',
        'payment_date',
        'approved_by_id',
        'declined_by_id',
        'status_id',
        'is_deleted',
        'created_datetime',
        'updated_datetime',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'withdrawal_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getAllWithdrawals($memberId = null, $shareId = null)
    {
        $query = DB::table('withdrawals')
            ->select(
                'withdrawals.*',
                'status_lookup.*',
                DB::raw('CONCAT(member.firstname, " ", member.lastname) as member_name'),
                DB::raw('CONCAT(approver.firstname, " ", approver.lastname) as approver_name'),
                DB::raw('CONCAT(decliner.firstname, " ", decliner.lastname) as decliner_name')
            )
            ->join('users as member', 'member.user_id', '=', 'withdrawals.user_id')
            ->leftJoin('users as approver', 'approver.user_id', '=', 'withdrawals.approved_by_id')
            ->leftJoin('users as decliner', 'decliner.user_id', '=', 'withdrawals.declined_by_id')
            ->join('status_lookup', 'status_lookup.status_lookup_id', '=', 'withdrawals.status_id')
            ->where('withdrawals.is_deleted', '=', 0)
            ->orderBy('withdrawals.created_datetime', 'DESC');

        if (!empty($memberId)) {
            $query->where('withdrawals.user_id', '=', $memberId);
            $query->orderBy('withdrawals.created_datetime', 'DESC');
        }

        if (!empty($shareId)) {
            $query->where('withdrawals.withdrawal_id', '=', $shareId);
            return $query->first();
        }

        return $query->get();
    }

    public function getWithdrawals($shareId)
    {
        return $this->getAllWithdrawals(null, $shareId);
    }

    public function getMemberWithdrawals($memberId)
    {
        return $this->getAllWithdrawals($memberId);
    }
}
