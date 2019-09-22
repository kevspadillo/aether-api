<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    public const PENDING  = 1;
    public const VERIFIED = 2;
    public const APPROVED = 3;
    public const REJECTED = 4;
    public const ACTIVE   = 5;
    public const INACTIVE = 6;

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'user_status_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
