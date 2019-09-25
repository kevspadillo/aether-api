<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SavingsHistory extends Model
{

    protected $table = 'savings_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'savings_history_id',
        'user_id',
        'history_title',
        'history_note',
        'created_datetime',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'savings_history_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
