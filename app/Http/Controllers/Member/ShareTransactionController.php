<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ShareTransactions;
use App\Models\StatusLookup;
use App\Helpers\MemberHelper;
use JWTAuth;
use App\Http\Requests\ShareRequest;
use App\Http\Controllers\Controller;

class ShareTransactionController extends Controller
{

    private $ShareTransactions;

    public function __construct(
        ShareTransactions $ShareTransactions
    ) {
        $this->ShareTransactions = $ShareTransactions;
    }
    
    public function index($memberId)
    {
        $memberShareTransactions = $this->ShareTransactions->getMemberShareTransactions($memberId);

        return response()->json(['data' => $memberShareTransactions]);
    }
}
