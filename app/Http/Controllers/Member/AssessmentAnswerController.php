<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;

use App\Models\MemberAnswer;

class AssessmentAnswerController extends Controller
{
    public function store(Request $Request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = $Request->all();

        $answer = MemberAnswer::where('assesment_question_id', $data['assessment_question_id'])
            ->where('user_id', $user->user_id)
            ->first();

        if ($answer) {
            $answer->assessment_question_choice_id = $data['assessment_question_choice_id'];
            $answer->save();
        } else {
            $answer = new MemberAnswer();
            $answer->assesment_question_id         = $data['assessment_question_id'];
            $answer->user_id                       = $user->user_id;
            $answer->assessment_question_choice_id = $data['assessment_question_choice_id'];
            $answer->save();
        }

        $response = MemberAnswer::with('evaluation')->find($answer->member_answer_id);
        $response->evaluation->makeVisible('is_correct');
        return response()->json($response);
    }
}
