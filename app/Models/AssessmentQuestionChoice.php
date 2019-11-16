<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestionChoice extends Model
{

    protected $table = 'assessment_question_choices';

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'assessment_question_choice_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    protected $hidden = [
        'is_correct', 'datetime_created', 'datetime_updated', 'is_active'
    ];

    public function question()
    {
        return $this->belongsTo('App\Models\AssessmentQuestion', 'assesment_question_id', 'assesment_question_id');
    }
}
