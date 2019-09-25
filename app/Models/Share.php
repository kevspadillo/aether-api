<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Share extends Model
{
    const DEFAULT_RATE_PER_SHARE = 50;

    protected $notFoundMessage = 'The book could not be found';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'reference_code',
        'number_of_shares',
        'rate_per_share',
        'term_id',
        'payment_date',
        'approved_by_id',
        'declined_by_id',
        'status_id',
        'is_deleted',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'share_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getAllShares($memberId = null, $shareId = null)
    {
        $query = DB::table('shares')
            ->select(
                'shares.*',
                'status_lookup.*',
                DB::raw('(shares.rate_per_share * shares.number_of_shares) as total_amount'),
                DB::raw('CONCAT(member.firstname, " ", member.lastname) as member_name'),
                DB::raw('CONCAT(approver.firstname, " ", approver.lastname) as approver_name'),
                DB::raw('CONCAT(decliner.firstname, " ", decliner.lastname) as decliner_name')
            )
            ->join('users as member', 'member.user_id', '=', 'shares.user_id')
            ->leftJoin('users as approver', 'approver.user_id', '=', 'shares.approved_by_id')
            ->leftJoin('users as decliner', 'decliner.user_id', '=', 'shares.declined_by_id')
            ->join('status_lookup', 'status_lookup.status_lookup_id', '=', 'shares.status_id')
            ->orderBy('shares.created_datetime', 'DESC');

        if (!empty($memberId)) {
            $query->where('shares.user_id', '=', $memberId);
            $query->orderBy('shares.created_datetime', 'DESC');
        }

        if (!empty($shareId)) {
            $query->where('shares.share_id', '=', $shareId);
            return $query->first();
        }

        return $query->get();
    }

    public function getShare($shareId)
    {
        return $this->getAllShares(null, $shareId);
    }

    public function getMemberShares($memberId)
    {
        return $this->getAllShares($memberId);
    }
}
