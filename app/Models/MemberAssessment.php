<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberAssessment extends Model
{

    protected $table = 'member_assessments';

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'member_assesment_id';

    public $timestamps = false;

    public function answers() {
        return $this->hasMany('App\Models\MemberAnswer', 'user_id', 'user_id');
    }
}
