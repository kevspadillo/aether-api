<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    public const GUEST  = 1;
    public const CLIENT = 2;
    public const ADMIN  = 3;

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'user_type_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
