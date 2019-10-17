<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'permission_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
