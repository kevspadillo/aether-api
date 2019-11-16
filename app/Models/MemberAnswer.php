<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberAnswer extends Model
{

    protected $table = 'member_answers';

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'member_answer_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function evaluation() {
        return $this->hasOne('App\Models\AssessmentQuestionChoice', 'assessment_question_choice_id', 'assessment_question_choice_id');
    }
}
