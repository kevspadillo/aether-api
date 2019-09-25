<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Savings;
use App\Models\SavingsHistory;
use App\Models\StatusLookup;
use App\Helpers\MemberHelper;
use JWTAuth;
use App\Http\Requests\SavingsRequest;

class SavingsController extends Controller
{
    protected $Savings;
    protected $SavingsHistory;

    public function __construct(
        Savings $Savings,
        SavingsHistory $SavingsHistory
    ) {
        $this->Savings = $Savings;
        $this->SavingsHistory = $SavingsHistory;
    }

    public function index()
    {
        return response()->json(['data' => $this->Savings->getAllSavings()]);
    }

    public function show($id)
    {
        $Savings = $this->Savings->getSavings($id);

        if (!$Savings) {
            return response()->json(['message' => 'Savings Not Found.'], 404);
        }

        return response()->json(['data' => $Savings]);
    }

    public function approve(Request $Request, $id)
    {
        $Savings = $this->Savings::find($id);
        
        if (!$Savings) {
            return response()->json(["message" => "Savings Not Found."], 404);
        }

        $user = JWTAuth::parseToken()->authenticate();


        $forInactive = $this->Savings->where('user_id', '=', $Savings->user_id)
            ->where('status_id', '=', StatusLookup::APPROVED)->get();

        foreach ($forInactive as $forInactiveSavings) {
            $forInactiveSavings->update(['status_id' => StatusLookup::INACTIVE]);

            $DecactivateSavingsHistory = new SavingsHistory();
            $DecactivateSavingsHistory->savings_id       = $forInactiveSavings->savings_id;
            $DecactivateSavingsHistory->user_id        = $user->user_id;
            $DecactivateSavingsHistory->history_title  = 'Savings Updated';
            $DecactivateSavingsHistory->history_note   = 'Savings switched to inactive.';
            $DecactivateSavingsHistory->save();
        }

        $Savings->status_id = StatusLookup::APPROVED;
        $Savings->approved_by_id = $user->user_id;
        $Savings->save();

        $this->SavingsHistory->savings_id       = $id;
        $this->SavingsHistory->user_id        = $user->user_id;
        $this->SavingsHistory->history_title  = 'Savings Updated';
        $this->SavingsHistory->history_note   = 'Savings Approved';
        $this->SavingsHistory->save();

        return response()->json(['message' => 'Savings approved']);
    }

    public function disapprove(Request $Request, $id)
    {
        $Savings = $this->Savings::find($id);

        if (!$Savings) {
            return response()->json(['message' => 'Savings Not Found.'], 404);
        }

        $user = JWTAuth::parseToken()->authenticate();
        
        $Savings->status_id = StatusLookup::DECLINED;
        $Savings->declined_by_id = $user->user_id;
        $Savings->save();

        $this->SavingsHistory->savings_id     = $id;
        $this->SavingsHistory->user_id        = $user->user_id;
        $this->SavingsHistory->history_title  = 'Savings Updated';
        $this->SavingsHistory->history_note   = 'Savings Disapproved';
        $this->SavingsHistory->save();

        return response()->json(['message' => 'Savings disapproved.']);
    }

    public function destroy($id)
    {
        $Savings = $this->Savings::find($id);

        if (!$Savings) {
            return response()->json(['message' => 'Savings Not Found.'], 404);
        }

        $Savings->is_deleted = 1;
        $Savings->save();
        return response()->json(['message' => 'Savings deleted.']);
    }
}
