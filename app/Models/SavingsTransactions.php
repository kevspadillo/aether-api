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
}
