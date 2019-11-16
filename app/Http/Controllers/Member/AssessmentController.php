<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;

use App\Models\MemberAssessment;
use App\Models\MemberAnswer;
use App\Models\AssessmentQuestion;

class AssessmentController extends Controller
{

	public function index()
	{
        $user = JWTAuth::parseToken()->authenticate();
		$assessment = MemberAssessment::with(['answers', 'answers.evaluation'])->where('user_id', $user->user_id)->first();

		if ($assessment) {		
			foreach ($assessment->answers as $answer) {
				$answer->evaluation->makeVisible('is_correct');
			}
		}


		return response()->json($assessment);
	}

    public function store(Request $Request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        $questions = AssessmentQuestion::all();

        $answers = MemberAnswer::with('evaluation')->where('user_id', $user->user_id)->get();

        $totalScore = 0;
        foreach ($answers as $answer) {
        	if ($answer->evaluation->is_correct) {
        		$totalScore++;
        	}
        }

        $scorePercentage = number_format($totalScore / $questions->count() * 100, 2);
    	$MemberAssessment = new MemberAssessment();
    	$MemberAssessment->user_id               = $user->user_id;
    	$MemberAssessment->assessment_score      = $totalScore;
    	$MemberAssessment->assessment_score_rate = $scorePercentage;
    	$MemberAssessment->save();

    	return response()->json(['data' => $MemberAssessment]);
    }
}
