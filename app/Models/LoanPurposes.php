<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPurposes extends Model
{
    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'loan_purpose_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
