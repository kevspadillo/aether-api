<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Loan extends Model
{
    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'loan_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'loan_amount',
        'initial_payment_due_date',
        'payment_method_id',
        'verified_by',
        'status_id',
        'is_deleted',
        'verified_datetime',
    ];

    protected $hidden = [
        'user_id', 
        'payment_method_id'
    ];

    public function getMemberLoanApplications()
    {
        $query = DB::table('loans')
            ->select(
                'loans.*',
                'status_lookup.*',
                'loan_types.*',
                'loan_purposes.*',
                DB::raw('CONCAT(member.firstname, " ", member.lastname) as member_name'),
                DB::raw('CONCAT(verifier.firstname, " ", verifier.lastname) as verifier_name')
            )
            ->join('users as member', 'member.user_id', '=', 'loans.user_id')
            ->leftJoin('users as verifier', 'verifier.user_id', '=', 'loans.verified_by')
            ->join('status_lookup', 'status_lookup.status_lookup_id', '=', 'loans.status_id')
            ->join('loan_types', 'loan_types.loan_type_id', '=', 'loans.loan_type_id')
            ->join('loan_purposes', 'loan_purposes.loan_purpose_id', '=', 'loans.loan_purpose_id')
            ->orderBy('loans.create_datetime', 'DESC');

        return $query->get();
    }

    /**
     * Get the comments for the blog post.
     */
    public function coMakers()
    {
        return $this->hasMany('App\Models\LoanCoMaker', 'loan_id', 'loan_id');
    }

    public function loanStatus()
    {
        return $this->hasOne('App\Models\StatusLookup', 'status_lookup_id', 'status_id');
    }

    public function verifiedBy()
    {
        return $this->hasOne('App\User', 'user_id', 'verified_by');
    }

    public function paymentMethod()
    {
        return $this->hasOne('App\Models\LoanPaymentMethods', 'payment_method_id', 'payment_method_id');
    }

    public function loanType()
    {
        return $this->hasOne('App\Models\LoanTypes', 'loan_type_id', 'loan_type_id');
    }

    public function loanPurpose()
    {
        return $this->hasOne('App\Models\LoanPurposes', 'loan_purpose_id', 'loan_purpose_id');
    }

    public function member()
    {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }

    public function interestRate()
    {
        return $this->hasOne('App\Models\LoanSetting', 'loan_setting_id', 'interest_rate_id');
    }
}
