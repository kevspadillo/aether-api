<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDivision extends Model
{
    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'division_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
