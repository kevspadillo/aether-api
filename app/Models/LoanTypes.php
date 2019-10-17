<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanTypes extends Model
{
    public const EMERGENCY = 1;
    public const REGULAR   = 2;

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'loan_type_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
