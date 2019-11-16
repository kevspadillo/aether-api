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
use App\Models\LoanSetting;
use DB;

class LoanSettingsController extends Controller
{
    protected $Loan;
    protected $LoanHistory;

    public function __construct(
        LoanSetting $LoanSetting
    ) {
        $this->LoanSetting = $LoanSetting;
    }

    public function index() 
    {
        return response()->json(['data' => $this->LoanSetting->getLoanSettings()]);
    }

    public function show($loanSettingId)
    {
        return response()->json(['data' => $this->LoanSetting->getLoanSetting($loanSettingId)]);
    }

    public function store(Request $Request)
    {
        $data = $Request->all();

        $validator = Validator::make($data, [
            'interest_rate' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 409);
        }

        $validatedData = $validator->validated();

        DB::table('loan_settings')->where('is_active', '=', 1)->update(array('is_active' => 0));

        $user = JWTAuth::parseToken()->authenticate();
        $this->LoanSetting->user_id        = $user->user_id;
        $this->LoanSetting->interest_rate  = $validatedData['interest_rate'] / 100;
        $this->LoanSetting->is_active      = 1;
        $this->LoanSetting->save();

        return response()->json(['message' => 'Loan Setting successfully saved.'], 200);
    }

    public function update(Request $Request, $loanSettingId)
    {
        $LoanSetting = LoanSetting::find($loanSettingId);

        if (!$LoanSetting) {
            return response()->json(['message' => 'Loan Setting Not Found'], 404);
        }

        $data = $Request->all();

        $validator = Validator::make($data, [
            'interest_rate' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 409);
        }

        DB::table('loan_settings')->where('is_active', '=', 1)->update(array('is_active' => 0));

        $validatedData = $validator->validated();
        $LoanSetting->interest_rate  = $validatedData['interest_rate'];
        $LoanSetting->save();

        return response()->json(['message' => 'Loan Setting successfully updated.'], 200);
    }

    public function destroy($loanSettingId)
    {
        $LoanSetting = LoanSetting::find($loanSettingId);

        if (!$LoanSetting) {
            return response()->json(['message' => 'Loan Setting Not Found'], 404);
        }

        $activeSettings = LoanSetting::where('is_active', 1)->count();
        
        if ($activeSettings == 1) {
            return response()->json(['message' => 'Unable to delete, one setting must be active'], 409);
        }


        $LoanSetting->interest_rate  = $validatedData['interest_rate'];
        $LoanSetting->is_active      = $validatedData['is_active'];
        $LoanSetting->save();

        return response()->json(['message' => 'Loan Setting successfully updated.'], 200);
    }
}

