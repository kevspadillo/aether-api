<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\MemberTransactions;
use App\Models\LoanTransactions;
use App\User;

use JWTAuth;
class LoanTransactionsController extends Controller
{

    private $MemberTransactions;

    public function __construct(
        MemberTransactions  $MemberTransactions,
        LoanTransactions $LoanTransactions,
        User                $User
    ) {
        $this->MemberTransactions  = $MemberTransactions;
        $this->LoanTransactions = $LoanTransactions;
        $this->User                = $User;
    }

    public function index()
    {
        return response()->json(['data' => $this->MemberTransactions->getMemberTransactions('LOAN')]);
    }

    public function show($loanTransactionId)
    {
        $MemberTransaction = $this->MemberTransactions::find($loanTransactionId)->toArray();
        
        $loansTransactionData = $this->LoanTransactions->getLoanTransactions($loanTransactionId);

        $loansTransactions = [];
        $memberIds = [];

        foreach ($loansTransactionData as $loanTransaction) {
            
            $loansTransactions[$loanTransaction->member_id]['transactions'][] = $loanTransaction;

            $memberIds[] = $loanTransaction->member_id;
        }

        $members = $this->User->getMembers($memberIds);

        foreach ($members as $member) {
            $loansTransactions[$member->user_id]['member_record'] = $member;
        }

        $data = [];
        $data['records'] = $loansTransactions;
        $data['info'] = (array) $MemberTransaction;

        return response()->json(['data' => $data]);
    }

    public function update(Request $Request, $id)
    {
        $MemberTransaction = $this->MemberTransactions::find($id);

        if (!$MemberTransaction) {
            return response()->json(['message' => 'Share Transaction Not Found.'], 404);
        }

        $user = JWTAuth::parseToken()->authenticate();

        $MemberTransaction->is_posted = 1;
        $MemberTransaction->posted_by_user_id = $user->user_id;
        $MemberTransaction->posted_datetime = date('Y-m-d H:i:s');
        $MemberTransaction->save();

        return response()->json(
            [
                'message' => 'Share Transaction Successfully Posted.',
                'data' => $MemberTransaction
            ]
        );
    }
}
