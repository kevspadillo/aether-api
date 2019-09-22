<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

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
}
