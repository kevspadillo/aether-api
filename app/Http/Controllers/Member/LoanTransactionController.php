<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\LoanTransactions;
use App\Models\StatusLookup;
use App\Helpers\MemberHelper;
use JWTAuth;
use App\Http\Requests\ShareRequest;
use App\Http\Controllers\Controller;

class LoanTransactionController extends Controller
{
    private $LoanTransactions;

    public function __construct(
        LoanTransactions $LoanTransactions
    ) {
        $this->LoanTransactions = $LoanTransactions;
    }
    
    public function index($memberId)
    {
        $memberLoanTransactions = $this->LoanTransactions->getMemberLoanTransactions($memberId);

        return response()->json(['data' => $memberLoanTransactions]);
    }
}
