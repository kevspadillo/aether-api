<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Savings;
use App\Models\SavingsTransactions;
use JWTAuth;
use App\Http\Requests\SavingsRequest;
use App\Helpers\MemberHelper;
use App\Models\StatusLookup;

class SavingsController extends Controller
{
    protected $Savings;
    protected $SavingsTransactions;

    public function __construct(
        Savings $Savings,
        SavingsTransactions $SavingsTransactions
    ) {
        $this->Savings = $Savings;
        $this->SavingsTransactions = $SavingsTransactions;
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($this->Savings->getMemberSavings($user->user_id));
    }

    public function show($id)
    {
        $Savings = $this->Savings->getSavings($id);

        if (!$Savings) {
            return response()->json(['message' => 'Savings Not Found.'], 404);
        }

        return response()->json(['data' => $Savings]);
    }

    public function store(SavingsRequest $SavingsRequest)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $validated = $SavingsRequest->validated();

        $this->Savings->user_id             = $user->user_id;
        $this->Savings->amount              = $validated['amount'];
        $this->Savings->amount_in_words     = $validated['amount_in_words'];
        $this->Savings->payment_date        = date('Y-m-d', strtotime($validated['payment_date']));
        $this->Savings->check_number        = $validated['check_number'];
        $this->Savings->representative_name = $validated['representative_name'];
        $this->Savings->status_id           = StatusLookup::PENDING;
        $this->Savings->save();

        return response()->json(['data' => ['message' => 'success']]);
    }

    public function update(SavingsRequest $SavingsRequest, $id)
    {
        $Savings = Savings::findOrFail($id);
        
        $data = $SavingsRequest->validated();

        $this->Savings->amount              = $validated['amount'];
        $this->Savings->amount_in_words     = $validated['amount_in_words'];
        $this->Savings->payment_date        = date('Y-m-d', strtotime($validated['payment_date']));
        $this->Savings->check_number        = $validated['check_number'];
        $this->Savings->representative_name = $validated['representative_name'];
        $Savings->save();

        return response()->json(['data' => ['message' => 'success']]);
    }
    
    public function summary($id)
    {
        $shareTotal = $this->SavingsTransactions->getSavingsTransactionTotal($id);
        $ytdSavingsTotal = $this->SavingsTransactions->getYearToDateSavingsTransactionTotal($id);

        return response()->json(
            [
                'total_shares' => $shareTotal->share,
                'ytd_share'    => $ytdSavingsTotal->share_capital ?? 0,
            ]
        );
    }
}
