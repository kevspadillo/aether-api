<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use DB;

use App\Models\MemberDivision;
use JWTAuth;

use App\User;
use App\Models\Loan;
use App\Models\Share;
use App\Models\Savings;
use App\Models\UserStatus;
use App\Models\StatusLookup;

class DashboardController extends Controller
{
    public function membershipSummary() 
    {

        $query = DB::table('user_statuses')
            ->leftJoin('users', 'users.user_status_id', '=', 'user_statuses.user_status_id');

        $data = $query->get();


        $records = [];

        foreach ($data as $value) {
                
            if (!isset($records[$value->user_status])) {
                $records[$value->user_status] = 0;
            }

            if (!empty($value->user_id)) {
                $records[$value->user_status] += 1;
            }
        }

        return response()->json([
            'records'      => array_values($records),
            'labels'       => array_keys($records),
            'total'        => array_sum(array_values($records)),
            'grouped_data' => $records
        ]);

    }

    public function postedShares() {

        $query = DB::table('share_transactions AS st1')
            ->join('users', 'users.user_id', '=', 'st1.member_id')
            ->join('member_divisions', 'member_divisions.division_id', '=', 'users.division_id')
            ->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'st1.member_transaction_id')
            ->where('member_transactions.is_posted', '=', 1)
            ->where('member_transactions.transaction_type', '=', 'SHARE')
            ->where('st1.transaction_date', '=', function($query) {
                $query->select(DB::raw('MAX(st2.transaction_date)'))
                    ->from('share_transactions AS st2')
                    ->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'st2.member_transaction_id')
                    ->where('st1.member_id', '=', DB::raw('st2.member_id'))
                    ->where('member_transactions.is_posted', '=', 1)
                    ->where('member_transactions.transaction_type', '=', 'SHARE');
            });

        $data = $query->get();

        $divisions = MemberDivision::all();


        $records = [];
        foreach ($divisions as $division) {
            $records[$division->division] = 0;
        }

        foreach ($data as $value) {

            if (!empty($value->division)) {
                $records[$value->division] += $value->share;
            }
        }

        return response()->json([
            'records'      => array_values($records),
            'labels'       => array_keys($records),
            'total'        => array_sum(array_values($records)),
            'grouped_data' => $records
        ]);
    }

    public function postedSavings() {

        $query = DB::table('savings_transactions AS st1')
            ->join('users', 'users.user_id', '=', 'st1.member_id')
            ->join('member_divisions', 'member_divisions.division_id', '=', 'users.division_id')
            ->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'st1.member_transaction_id')
            ->where('member_transactions.is_posted', '=', 1)
            ->where('member_transactions.transaction_type', '=', 'SAVINGS')
            ->where('st1.transaction_date', '=', function($query) {
                $query->select(DB::raw('MAX(st2.transaction_date)'))
                    ->from('savings_transactions AS st2')
                    ->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'st2.member_transaction_id')
                    ->where('st1.member_id', '=', DB::raw('st2.member_id'))
                    ->where('member_transactions.is_posted', '=', 1)
                    ->where('member_transactions.transaction_type', '=', 'SAVINGS');
            });

        $data = $query->get();

        $divisions = MemberDivision::all();


        $records = [];
        foreach ($divisions as $division) {
            $records[$division->division] = 0;
        }

        foreach ($data as $value) {

            if (!empty($value->division)) {
                $records[$value->division] += $value->savings;
            }
        }

        return response()->json([
            'records'      => array_values($records),
            'labels'       => array_keys($records),
            'total'        => array_sum(array_values($records)),
            'grouped_data' => $records
        ]);
    }


    public function postedLoans() {

        $query = DB::table('loan_transactions AS lt1')
            ->join('users', 'users.user_id', '=', 'lt1.member_id')
            ->join('member_divisions', 'member_divisions.division_id', '=', 'users.division_id')
            ->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'lt1.member_transaction_id')
            ->where('member_transactions.is_posted', '=', 1)
            ->where('member_transactions.transaction_type', '=', 'LOAN')
            ->where('lt1.transaction_date', '=', function($query) {
                $query->select(DB::raw('MAX(lt2.transaction_date)'))
                    ->from('loan_transactions AS lt2')
                    ->join('member_transactions', 'member_transactions.member_transaction_id', '=', 'lt2.member_transaction_id')
                    ->where('lt1.member_id', '=', DB::raw('lt2.member_id'))
                    ->where('member_transactions.is_posted', '=', 1)
                    ->where('member_transactions.transaction_type', '=', 'LOAN');
            });

        $data = $query->get();

        $divisions = MemberDivision::all();


        $records = [];
        foreach ($divisions as $division) {
            $records[$division->division] = 0;
        }

        foreach ($data as $value) {

            if (!empty($value->division)) {
                $records[$value->division] += $value->remaining_loan;
            }
        }

        return response()->json([
            'records'      => array_values($records),
            'labels'       => array_keys($records),
            'total'        => array_sum(array_values($records)),
            'grouped_data' => $records
        ]);
    }

    public function countSummary()
    {
        
        $newMembers = User::where('user_status_id', '=', UserStatus::PENDING)->count();
        $newShares  = Share::where('status_id', '=', StatusLookup::PENDING)->count();
        $newSavings = Savings::where('status_id', '=', StatusLookup::PENDING)->count();
        $newLoans   = Loan::where('status_id', '=', StatusLookup::PENDING)->count();

        return response()->json(
            [
                'new_members' => $newMembers,
                'new_shares'  => $newShares,
                'new_savings' => $newSavings,
                'new_loans'   => $newLoans
            ]
        );
    }
}

