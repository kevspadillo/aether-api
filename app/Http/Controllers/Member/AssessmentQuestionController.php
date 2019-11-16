<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;

use App\Models\AssessmentQuestion;

class AssessmentQuestionController extends Controller
{
    public function index()
    {
        $questions = AssessmentQuestion::with('choices')->get();
        return response()->json($questions);
    }
}
