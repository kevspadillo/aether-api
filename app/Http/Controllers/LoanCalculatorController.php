<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CalculateLoanRequest;
use JWTAuth;
use App\Models\LoanSetting;

class LoanCalculatorController extends Controller
{

    const DEFAULT_INTEREST_RATE = 0.16;

    public function __construct(
        LoanSetting $LoanSetting
    ) {
        $this->LoanSetting = $LoanSetting;
    }

    public function calculateLoan(CalculateLoanRequest $Request)
    {

        $setting = LoanSetting::where('is_active', 1)->first();

        $validated = $Request->validated();

        $data          = $Request->all();
        $totalInterest = $data['loan_amount'] * $setting->interest_rate;
        $monthlyRate   = $totalInterest * (30 / 360);
        $grossMonthly  = $data['loan_amount'] / $data['months_to_pay'];
        $netMonthly    = $grossMonthly + $monthlyRate;

        return response()->json(['data' => [
            'monthly_pay' => $netMonthly,
            'bi_monthly_pay' => $netMonthly / 2
        ]]);
    }
}
