<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPaymentMethods extends Model
{
    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'payment_method_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    protected $hidden = [
        'payment_method_id'
    ];
}
