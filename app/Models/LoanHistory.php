<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanHistory extends Model
{
    protected $table = 'loan_history';

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'loan_history_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
