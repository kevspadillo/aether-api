<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ADMIN   = 1;
    public const MANAGER = 2;
    public const GUEST   = 3;
    public const MEMBER  = 4;

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'role_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
