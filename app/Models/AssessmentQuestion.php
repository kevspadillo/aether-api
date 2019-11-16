<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{

    protected $table = 'assessment_questions';

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'assessment_question_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    protected $hidden = [
        'user_id', 'datetime_created', 'datetime_updated', 'is_active'
    ];

    public function choices()
    {
        return $this->hasMany('App\Models\AssessmentQuestionChoice', 'assessment_question_id', 'assessment_question_id')
            ->where('is_active', '=', 1);
    }
}
