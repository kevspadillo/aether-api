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
use App\Models\SavingsHistory;

class SavingsController extends Controller
{
    protected $Savings;
    protected $SavingsTransactions;

    public function __construct(
        Savings $Savings,
        SavingsTransactions $SavingsTransactions,
        SavingsHistory $SavingsHistory
    ) {
        $this->Savings = $Savings;
        $this->SavingsTransactions = $SavingsTransactions;
        $this->SavingsHistory = $SavingsHistory;
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

        $this->SavingsHistory->user_id       = $user->user_id;
        $this->SavingsHistory->savings_id    = $this->Savings->savings_id;
        $this->SavingsHistory->history_title = "Saving Created.";
        $this->SavingsHistory->history_note  = "Filed a new savings contribution";
        $this->SavingsHistory->save();

        return response()->json(['data' => ['message' => 'success']]);
    }

    public function update(SavingsRequest $SavingsRequest, $id)
    {
        $Savings = Savings::findOrFail($id);
        
        $validated = $SavingsRequest->validated();

        $user = JWTAuth::parseToken()->authenticate();
        
        $Savings->amount              = $validated['amount'];
        $Savings->amount_in_words     = $validated['amount_in_words'];
        $Savings->payment_date        = date('Y-m-d', strtotime($validated['payment_date']));
        $Savings->check_number        = $validated['check_number'];
        $Savings->representative_name = $validated['representative_name'];
        $Savings->save();

        $this->SavingsHistory->user_id       = $user->user_id;
        $this->SavingsHistory->savings_id    = $Savings->savings_id;
        $this->SavingsHistory->history_title = "Saving Updated.";
        $this->SavingsHistory->history_note  = "Updated a new savings contribution";
        $this->SavingsHistory->save();

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

    public function destroy($id)
    {
        $Saving = Savings::find($id);

        if (!$Saving) {
            return response()->json(['message' => 'Saving Not Found.'], 404);
        }

        $Saving->is_deleted = 1;
        $Saving->save();

        $user = JWTAuth::parseToken()->authenticate();

        $this->SavingsHistory->user_id       = $user->user_id;
        $this->SavingsHistory->savings_id    = $id;
        $this->SavingsHistory->history_title = "Saving Deleted.";
        $this->SavingsHistory->history_note  = "Deleted a savings contribution";
        $this->SavingsHistory->save();

        return response()->json(['data' => ['message' => 'success']]);
    }
}
