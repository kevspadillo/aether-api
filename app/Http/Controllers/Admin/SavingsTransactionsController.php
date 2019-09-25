<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\MemberTransactions;
use App\Models\SavingsTransactions;
use App\User;

use JWTAuth;
class SavingsTransactionsController extends Controller
{

    private $MemberTransactions;

    public function __construct(
        MemberTransactions  $MemberTransactions,
        SavingsTransactions $SavingsTransactions,
        User                $User
    ) {
        $this->MemberTransactions  = $MemberTransactions;
        $this->SavingsTransactions = $SavingsTransactions;
        $this->User                = $User;
    }

    public function index()
    {
        return response()->json(['data' => $this->MemberTransactions->getMemberTransactions('SAVINGS')]);
    }

    public function show($savingsTransactionId)
    {
        $MemberTransaction = $this->MemberTransactions::find($savingsTransactionId)->toArray();
        
        $savingsTransactionData = $this->SavingsTransactions->getSavingsTransactions($savingsTransactionId);

        $savingsTransactions = [];
        $memberIds = [];

        foreach ($savingsTransactionData as $shareTransaction) {
            
            $savingsTransactions[$shareTransaction->member_id]['transactions'][] = $shareTransaction;

            $memberIds[] = $shareTransaction->member_id;
        }

        $members = $this->User->getMembers($memberIds);

        foreach ($members as $member) {
            $savingsTransactions[$member->user_id]['member_record'] = $member;
        }

        $data = [];
        $data['records'] = $savingsTransactions;
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
