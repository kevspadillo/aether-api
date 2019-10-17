<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class LoanCoMaker extends Model
{

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'loan_co_maker_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getCoMakerRequests($memberId)
    {
        $query = DB::table('loan_co_makers')
            ->select(
                'l.*',
                'loan_co_makers.*',
                DB::raw('cmsl.status_lookup_name as co_maker_status'),
                DB::raw('cmsl.status_lookup_id as co_maker_status_id'),
                DB::raw('lsl.status_lookup_name as loan_status'),
                DB::raw('CONCAT(u.firstname, " ", u.lastname) as loan_applicant')
            )
            ->join('loans as l', 'l.loan_id', '=', 'loan_co_makers.loan_id')
            ->join('users as u', 'u.user_id', '=', 'l.user_id')
            ->join('status_lookup as lsl', 'lsl.status_lookup_id', '=', 'l.status_id')
            ->join('status_lookup as cmsl', 'cmsl.status_lookup_id', '=', 'loan_co_makers.status_id')
            ->where('loan_co_makers.co_maker_user_id', '=', $memberId);

        return $query->get();
    }
}