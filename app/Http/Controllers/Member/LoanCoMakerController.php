<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoanRequest;
use JWTAuth;

use App\Models\Loan;
use App\Models\LoanHistory;
use App\Models\LoanCoMaker;
use App\Models\StatusLookup;

class LoanCoMakerController extends Controller
{
    public function __construct(
        Loan $Loans,
        LoanHistory $LoanHistory,
        LoanCoMaker $LoanCoMaker
    ) {
        $this->Loans        = $Loans;
        $this->LoanHistory  = $LoanHistory;
        $this->LoanCoMaker = $LoanCoMaker;
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['data' => $this->LoanCoMaker->getCoMakerRequests($user->user_id)]);
    }

    public function update(Request $Request, $id)
    {
        $LoanCoMaker = LoanCoMaker::find($id);

        if (!$LoanCoMaker) {
            return response()->json(['message' => 'Loan co-maker not found.'], 404);
        }

        $data = $Request->all();

        $user = JWTAuth::parseToken()->authenticate();

        switch ($data['action']) {
            case 'APPROVE':
                $LoanCoMaker->status_id = StatusLookup::APPROVED;
                $historyData = [
                    'title'   => 'Loan Co-Maker Approved',
                    'message' => sprintf("%s %s approved to be a loan co-maker.", $user->firstname, $user->lastname)
                ];
                break;
            case 'DECLINE':
                $LoanCoMaker->status_id = StatusLookup::DECLINED;
                $historyData = [
                    'title'   => 'Loan Co-Maker Approved',
                    'message' => sprintf("%s %s approved to be a loan co-maker.", $user->firstname, $user->lastname)
                ];
                break;
        }

        $LoanCoMaker->verified_datetime = date('Y-m-d H:i:s');
        $result = $LoanCoMaker->save();

        if ($result) {
            $this->LoanHistory->user_id       = $user->user_id;
            $this->LoanHistory->loan_id       = $LoanCoMaker->loan_id;
            $this->LoanHistory->history_title = $historyData['title'];
            $this->LoanHistory->history_note  = $historyData['message'];

            $this->LoanHistory->save();
        }

        return response()->json(['data' => 'Loan Co-Maker Updated']);
    }
}
