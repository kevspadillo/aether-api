<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Savings extends Model
{
    protected $notFoundMessage = 'The book could not be found';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'savings_id',
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
    public $primaryKey = 'savings_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getAllSavings($memberId = null, $shareId = null)
    {
        $query = DB::table('savings')
            ->select(
                'savings.*',
                'status_lookup.*',
                DB::raw('CONCAT(member.firstname, " ", member.lastname) as member_name'),
                DB::raw('CONCAT(approver.firstname, " ", approver.lastname) as approver_name'),
                DB::raw('CONCAT(decliner.firstname, " ", decliner.lastname) as decliner_name')
            )
            ->join('users as member', 'member.user_id', '=', 'savings.user_id')
            ->leftJoin('users as approver', 'approver.user_id', '=', 'savings.approved_by_id')
            ->leftJoin('users as decliner', 'decliner.user_id', '=', 'savings.declined_by_id')
            ->join('status_lookup', 'status_lookup.status_lookup_id', '=', 'savings.status_id')
            ->orderBy('savings.created_datetime', 'DESC');

        if (!empty($memberId)) {
            $query->where('savings.user_id', '=', $memberId);
            $query->orderBy('savings.created_datetime', 'DESC');
        }

        if (!empty($shareId)) {
            $query->where('savings.savings_id', '=', $shareId);
            return $query->first();
        }

        return $query->get();
    }

    public function getSavings($shareId)
    {
        return $this->getAllSavings(null, $shareId);
    }

    public function getMemberSavings($memberId)
    {
        return $this->getAllSavings($memberId);
    }
}
