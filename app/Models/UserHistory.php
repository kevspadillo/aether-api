<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserHistory extends Model
{

    protected $table = 'user_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_history_id',
        'user_id',
        'member_id',
        'history_title',
        'history_note',
        'created_datetime',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'user_history_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
