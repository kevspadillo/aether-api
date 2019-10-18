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
use App\Models\ShareTransactions;
use App\User;

class LoanController extends Controller
{
    public function __construct(
        Loan $Loans,
        LoanHistory $LoanHistory,
        LoanCoMaker $LoanCoMaker,
        ShareTransactions $ShareTransactions
    ) {
        $this->Loans             = $Loans;
        $this->LoanHistory       = $LoanHistory;
        $this->LoanCoMaker       = $LoanCoMaker;
        $this->ShareTransactions = $ShareTransactions;
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $Loans = User::with(['loans.loanStatus', 'loans.verifiedBy','loans.paymentMethod'])
            ->find($user->user_id)->loans;
        return response()->json(['data' => $Loans]);
    }

    public function show($loanId)
    {
        $Loan = Loan::with(['loanStatus', 'verifiedBy', 'paymentMethod', 'loanType', 'loanPurpose'])->find($loanId);
        if (!$Loan) {
            return response()->json(['message' => 'Loan Not Found.'], 404);
        }
        return response()->json(['data' => $Loan]);
    }

    function store(LoanRequest $LoanRequest)
    {
        $validated = $LoanRequest->validated();

        $user = JWTAuth::parseToken()->authenticate();

        $this->Loans->user_id                  = $user->user_id;
        $this->Loans->loan_amount              = $validated['loan_amount'];
        $this->Loans->initial_payment_due_date = date('Y-m-d', strtotime($validated['payment_date']));
        $this->Loans->payment_method_id        = $validated['payment_method_id'];
        $this->Loans->loan_type_id             = $validated['loan_type'];
        $this->Loans->loan_purpose_id          = $validated['loan_purpose_id'];
        $this->Loans->status_id                = StatusLookup::PENDING;
        $this->Loans->save();

        $loanId = $this->Loans->loan_id;


        $shareTotal = $this->ShareTransactions->getShareTransactionTotal($user->user_id);
        $loanableAmount = ($shareTotal->share * 2);
        
        $exeededAmount = $validated['loan_amount'] - $loanableAmount;

        if ($loanableAmount > $validated['loan_amount']) {        
            if (!empty($validated['co_makers'])) {        
                $individualRequiredShare = ($exeededAmount / count($validated['co_makers']));

                $loanCoMakers = [];
                foreach ($validated['co_makers'] as $coMakerId) {
                    $loanCoMakers[] = [
                        'loan_id'          => $loanId,
                        'co_maker_user_id' => $coMakerId,
                        'status_id'        => StatusLookup::PENDING,
                        'freeze_amount'    => $individualRequiredShare
                    ];
                }
                $this->LoanCoMaker->insert($loanCoMakers);
            }
        }

        $this->LoanHistory->user_id       = $user->user_id;
        $this->LoanHistory->loan_id       = $loanId;
        $this->LoanHistory->history_title = "Loan Applied.";
        $this->LoanHistory->history_note  = "Loan application applied.";
        $this->LoanHistory->save();

        return response()->json(['data' => ['message' => 'success']]);
    }

    public function update(LoanRequest $LoanRequest, $loanId)
    {
        $Loan = Loan::find($loanId);

        $coMakers = $Loan->coMakers;

        if (StatusLookup::APPROVED == $Loan->status_id) {
            return response()->json(['message' => 'Approved loans cannot be updated.'], 409);
        }

        $validated = $LoanRequest->validated();

        $user = JWTAuth::parseToken()->authenticate();

        $Loan->user_id                  = $user->user_id;
        $Loan->loan_amount              = $validated['loan_amount'];
        $Loan->initial_payment_due_date = date('Y-m-d', strtotime($validated['payment_date']));
        $Loan->payment_method_id        = $validated['payment_method_id'];
        $Loan->loan_type_id             = $validated['loan_type'];
        $Loan->loan_purpose_id          = $validated['loan_purpose_id'];
        $Loan->status_id                = StatusLookup::PENDING;

        if ($Loan->save()) {        
            $this->LoanHistory->user_id       = $user->user_id;
            $this->LoanHistory->loan_id       = $loanId;
            $this->LoanHistory->history_title = "Loan Applied.";
            $this->LoanHistory->history_note  = "Loan application updated.";
            $this->LoanHistory->save();
        }

        $existingCoMakerIds = [];
        foreach ($coMakers as $coMaker) {
            $existingCoMakerIds[] = $coMaker->co_maker_user_id;
        }

        if (!empty($coMakers)) {        
            foreach ($coMakers as $coMaker) {
                if (!in_array($coMaker->co_maker_user_id, $validated['co_makers'])) {
                    $coMaker->delete();
                }
            }
        }

        if (!empty($validated['co_makers'])) {
            $newCoMakers = array_diff($validated['co_makers'], $existingCoMakerIds);
            $loanCoMakers = [];
            foreach ($newCoMakers as $coMakerId) {
                $loanCoMakers[] = [
                    'loan_id'          => $loanId,
                    'co_maker_user_id' => $coMakerId,
                    'status_id'        => StatusLookup::PENDING
                ];
            }

            $this->LoanCoMaker->insert($loanCoMakers);
        }

        return response()->json(['data' => ['message' => 'Loan updated.']]);
    }

    public function destroy($loanId)
    {
        $Loan = Loan::find($loanId);

        if (!$Loan) {
            return response()->json(['message' => 'Loan not found.'], 404);
        }

        $Loan->is_deleted = 1;
        $Loan->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->LoanHistory->user_id       = $user->user_id;
        $this->LoanHistory->loan_id       = $loanId;
        $this->LoanHistory->history_title = "Loan Deleted.";
        $this->LoanHistory->history_note  = "Loan application deleted.";
        $this->LoanHistory->save();

        return response()->json(['data' => ['message' => 'Loan deleted.']]);
    }
}
