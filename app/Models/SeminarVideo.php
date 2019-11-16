<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class SeminarVideo extends Model
{
    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'seminar_video_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;
}
