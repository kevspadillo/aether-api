<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Loan;
use App\Models\LoanHistory;
use App\Rules\ValidateLoanApproval;
use Illuminate\Validation\Rule;
use JWTAuth;
use App\Models\StatusLookup;

class LoanController extends Controller
{
    protected $Loan;
    protected $LoanHistory;

    public function __construct(
        Loan $Loan,
        LoanHistory $LoanHistory
    ) {
        $this->Loan = $Loan;
        $this->LoanHistory = $LoanHistory;
    }

    public function index() 
    {
        return response()->json(['data' => $this->Loan->getMemberLoanApplications()]);
    }

    public function store(Request $Request)
    {

    }

    public function destroy($loanId)
    {

    }

    public function update(Request $Request, $id)
    {

    }

    public function verifyLoan(Request $Request, $id)
    {
        $validatedData = $Request->validate([
            'status' => [
                'required', 
                new ValidateLoanApproval($id),
                Rule::in([2, 3])
            ],
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        $Loan = Loan::find($id);
        $Loan->status_id         = $validatedData['status'];
        $Loan->verified_by       = $user->user_id;
        $Loan->verified_datetime = date('Y-m-d H:i:s'); 

        $loanResult = $Loan->save();

        if ($loanResult) {

            if ($validatedData['status'] == StatusLookup::APPROVED) {
                $historyTitle = 'Loan Approved';
            }
            
            if ($validatedData['status'] == StatusLookup::DECLINED) {
                $historyTitle = 'Loan Disapproved';
            }

            $this->LoanHistory->user_id       = $user->user_id;
            $this->LoanHistory->loan_id       = $id;
            $this->LoanHistory->history_title = $historyTitle;
            $this->LoanHistory->history_note  = 'Loan Application status updated.';

            $this->LoanHistory->save();
        }

        return response()->json(['data' => $historyTitle]);
    }
}

