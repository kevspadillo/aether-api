<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusLookup extends Model
{
    const PENDING  = 1;
    const APPROVED = 2;
    const DECLINED = 3;

    protected $table = 'status_lookup';

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'status_lookup_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
