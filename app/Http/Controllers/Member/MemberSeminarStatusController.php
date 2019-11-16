<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Share;
use App\Models\ShareTransactions;
use JWTAuth;
use App\Http\Requests\ShareRequest;
use App\Helpers\MemberHelper;
use App\Models\MemberSeminarStatus;

class MemberSeminarStatusController extends Controller
{

    private $MemberSeminarStatus;

    public function __construct(
        MemberSeminarStatus $MemberSeminarStatus
    ) {
        $this->MemberSeminarStatus = $MemberSeminarStatus;
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $videos = $this->MemberSeminarStatus->getMemberSeminarVideos($user->user_id);

        $response = [];

        foreach ($videos as $video) {
        	$response[$video->seminar_video_id] = $video;
        }

        return response()->json(['data' => $response]);
    }

    public function update(Request $Request, $seminarVideoId)
    {

    	$data = $Request->all();
        $user = JWTAuth::parseToken()->authenticate();

    	$video = $this->MemberSeminarStatus->getMemberSeminarStatus($seminarVideoId, $user->user_id);	

    	$videoStatus = MemberSeminarStatus::find($video->member_seminar_status_id);

    	// Update only if video is not completed
    	if ($video->watch_duration < $video->video_duration ) {
	    	$videoStatus->watch_duration = ($data['duration'] / 1000);
	    	$videoStatus->save();
    	}
    }
}
