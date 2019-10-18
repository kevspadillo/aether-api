<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Withdrawals;
use JWTAuth;
use App\Http\Requests\WithdrawalsRequest;
use App\Helpers\MemberHelper;
use App\Models\StatusLookup;
use App\Models\WithdrawalHistory;

class WithdrawalController extends Controller
{
    protected $Withdrawals;
    protected $WithdrawalHistory;

    public function __construct(
        Withdrawals $Withdrawals,
        WithdrawalHistory $WithdrawalHistory
    ) {
        $this->Withdrawals = $Withdrawals;
        $this->WithdrawalHistory = $WithdrawalHistory;
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($this->Withdrawals->getMemberWithdrawals($user->user_id));
    }

    public function show($id)
    {
        $Withdrawals = $this->Withdrawals->getWithdrawals($id);

        if (!$Withdrawals) {
            return response()->json(['message' => 'Withdrawals Not Found.'], 404);
        }

        return response()->json(['data' => $Withdrawals]);
    }

    public function store(WithdrawalsRequest $WithdrawalsRequest)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $validated = $WithdrawalsRequest->validated();

        $this->Withdrawals->user_id             = $user->user_id;
        $this->Withdrawals->amount              = $validated['amount'];
        $this->Withdrawals->amount_in_words     = $validated['amount_in_words'];
        $this->Withdrawals->payment_date        = date('Y-m-d', strtotime($validated['payment_date']));
        $this->Withdrawals->check_number        = $validated['check_number'];
        $this->Withdrawals->representative_name = $validated['representative_name'];
        $this->Withdrawals->status_id           = StatusLookup::PENDING;
        $this->Withdrawals->save();

        $this->WithdrawalHistory->user_id       = $user->user_id;
        $this->WithdrawalHistory->withdrawal_id = $this->Withdrawals->withdrawal_id;
        $this->WithdrawalHistory->history_title = "Saving Created.";
        $this->WithdrawalHistory->history_note  = "Filed a new savings contribution";
        $this->WithdrawalHistory->save();

        return response()->json(['data' => ['message' => 'success']]);
    }

    public function update(WithdrawalsRequest $WithdrawalsRequest, $id)
    {
        $Withdrawals = Withdrawals::findOrFail($id);
        
        $validated = $WithdrawalsRequest->validated();

        $user = JWTAuth::parseToken()->authenticate();
        
        $Withdrawals->amount              = $validated['amount'];
        $Withdrawals->amount_in_words     = $validated['amount_in_words'];
        $Withdrawals->payment_date        = date('Y-m-d', strtotime($validated['payment_date']));
        $Withdrawals->check_number        = $validated['check_number'];
        $Withdrawals->representative_name = $validated['representative_name'];
        $Withdrawals->save();

        $this->WithdrawalHistory->user_id        = $user->user_id;
        $this->WithdrawalHistory->withdrawal_id  = $Withdrawals->withdrawal_id;
        $this->WithdrawalHistory->history_title  = "Saving Updated.";
        $this->WithdrawalHistory->history_note   = "Updated a new savings contribution";
        $this->WithdrawalHistory->save();

        return response()->json(['data' => ['message' => 'success']]);
    }

    public function destroy($id)
    {
        $Withdrawal = Withdrawals::find($id);

        if (!$Withdrawal) {
            return response()->json(['message' => 'Saving Not Found.'], 404);
        }

        $Withdrawal->is_deleted = 1;
        $Withdrawal->save();

        $user = JWTAuth::parseToken()->authenticate();

        $this->WithdrawalHistory->user_id       = $user->user_id;
        $this->WithdrawalHistory->withdrawal_id = $id;
        $this->WithdrawalHistory->history_title = "Saving Deleted.";
        $this->WithdrawalHistory->history_note  = "Deleted a savings contribution";
        $this->WithdrawalHistory->save();

        return response()->json(['data' => ['message' => 'success']]);
    }
}
