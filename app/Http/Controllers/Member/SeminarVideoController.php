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
use App\Models\SeminarVideo;

class SeminarVideoController extends Controller
{
    public function index()
    {
        return response()->json(['data' => SeminarVideo::where('is_active', 1)->get()]);
    }
}
