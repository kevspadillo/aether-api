<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\SavingsTransactions;
use App\Models\StatusLookup;
use App\Helpers\MemberHelper;
use JWTAuth;
use App\Http\Requests\ShareRequest;
use App\Http\Controllers\Controller;

class SavingsTransactionController extends Controller
{

    private $SavingsTransactions;

    public function __construct(
        SavingsTransactions $SavingsTransactions
    ) {
        $this->SavingsTransactions = $SavingsTransactions;
    }
    
    public function index($memberId)
    {
        $memberSavingsTransactions = $this->SavingsTransactions->getMemberSavingsTransactions($memberId);

        return response()->json(['data' => $memberSavingsTransactions]);
    }
}
