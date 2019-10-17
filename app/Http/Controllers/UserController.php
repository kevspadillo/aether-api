<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\UserStatus;
use App\Models\UserType;
use App\Models\Role;
use App\Models\UserHistory;
use JWTAuth;

class UserController extends Controller
{
    protected $User;
    protected $UserHistory;

    public function __construct(
        User $User,
        UserHistory $UserHistory
    ) {
        $this->User        = $User;    
        $this->UserHistory = $UserHistory;    
    }

    public function index()
    {
        $users = $this->User->getAllUsers();


        foreach ($users as $user) {
            $user->profile_status = true;
            if (in_array(null, (array) $user)) {
                $user->profile_status = false;
            }
        }

        return response()->json(['data' => $users]);
    }

    public function show($id)
    {
        return $this->User::findOrFail($id);
    }

    public function store(Request $Request)
    {   
        $data = $Request->all();

        $validator = Validator::make($data, [
            'firstname'        => 'required',
            'middlename'       => 'required',
            'lastname'         => 'required',
            'gender'           => 'required',
            'civil_status'     => 'required',
            'tin_number'       => 'required',
            'sss_number'       => 'required',
            'email'            => 'required|unique:users|max:255',
            'password'         => 'required|min:8',
            'employee_type_id' => 'required',
            'division_id'      => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 409);
        }

        $this->User->firstname        = $data['firstname'];
        $this->User->middlename       = $data['middlename'];
        $this->User->lastname         = $data['lastname'];
        $this->User->gender           = $data['gender'];
        $this->User->civil_status     = $data['civil_status'];
        $this->User->tin_number       = $data['tin_number'];
        $this->User->sss_number       = $data['sss_number'];
        $this->User->email            = $data['email'];
        $this->User->contact_number   = (isset($data['contact_number']) ? $data['contact_number'] : null);
        $this->User->password         = Hash::make($data['password']);
        $this->User->role_id          = $data['user_type_id'];
        $this->User->employee_type_id = $data['employee_type_id'];
        $this->User->division_id      = $data['division_id'];
        $this->User->user_status_id   = UserStatus::PENDING;

        if (in_array($data['user_type_id'], [Role::ADMIN, Role::MANAGER])) {
            $this->User->user_status_id = UserStatus::ACTIVE;
        }

        $this->User->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->UserHistory->user_id        = $user->user_id;
        $this->UserHistory->member_id      = $this->User->user_id;
        $this->UserHistory->history_title  = "Member Created";
        $this->UserHistory->history_note   = "Member added.";
        $this->UserHistory->save();

        return response()->json([
            'message' => 'success',
            'data'    => ['user_id' => $this->User->user_id]
        ]);

    }

    public function update(Request $Request, $id)
    {

        $User = $this->User::findOrFail($id);

        $data = $Request->all();

        $validator = Validator::make($data, [
            'firstname'           => 'required',
            'middlename'          => 'required',
            'lastname'            => 'required',
            'gender'              => 'required',
            'civil_status'        => 'required',
            'tin_number'          => 'required',
            'sss_number'          => 'required',
            'email'               => 'required|unique:users,email,' . $id . ',user_id|max:255',
            'landline_number'     => 'required',
            'mobile_number'       => 'required',
            'nationality'         => 'required',
            'mailing_address'     => 'required',
            'employee_type_id'    => 'required',
            'division_id'         => 'required',
            'other_income_source' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 409);
        }

        $User->firstname           = $data['firstname'];
        $User->middlename          = $data['middlename'];
        $User->lastname            = $data['lastname'];
        $User->gender              = $data['gender'];
        $User->civil_status        = $data['civil_status'];
        $User->tin_number          = $data['tin_number'];
        $User->sss_number          = $data['sss_number'];
        $User->email               = $data['email'];
        $User->landline_number     = $data['landline_number'];
        $User->mobile_number       = $data['mobile_number'];
        $User->nationality         = $data['nationality'];
        $User->mailing_address     = $data['mailing_address'];
        $User->employee_type_id    = $data['employee_type_id'];
        $User->division_id         = $data['division_id'];
        $User->other_income_source = $data['other_income_source'];
        
        $User->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->UserHistory->user_id        = $user->user_id;
        $this->UserHistory->member_id      = $User->user_id;
        $this->UserHistory->history_title  = "Member Updated";
        $this->UserHistory->history_note   = "Member information has been updated.";
        $this->UserHistory->save();

        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {

    }

    public function approveMember(Request $Request, $id)
    {
        $User = $this->User::findOrFail($id);
        $User->user_status_id = UserStatus::APPROVED;
        $User->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->UserHistory->user_id        = $user->user_id;
        $this->UserHistory->member_id      = $id;
        $this->UserHistory->history_title  = "Member Updated";
        $this->UserHistory->history_note   = "Member has been approved";
        $this->UserHistory->save();

        return response()->json(['message' => 'Member approved']);
    }

    public function activateMember(Request $Request, $id)
    {
        $User = $this->User::findOrFail($id);
        $User->user_status_id = UserStatus::APPROVED;
        $User->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->UserHistory->user_id        = $user->user_id;
        $this->UserHistory->member_id      = $id;
        $this->UserHistory->history_title  = "Member Updated";
        $this->UserHistory->history_note   = "Member has been activated";
        $this->UserHistory->save();

        return response()->json(['message' => 'Member activated']);
    }

    public function disapproveMember(Request $Request, $id)
    {
        $User = $this->User::findOrFail($id);
        $User->role_id = Role::MEMBER;
        $User->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->UserHistory->user_id        = $user->user_id;
        $this->UserHistory->member_id      = $id;
        $this->UserHistory->history_title  = "Member Updated";
        $this->UserHistory->history_note   = "Member has been disapproved";
        $this->UserHistory->save();

        return response()->json(['message' => 'Member disapproved.']);
    }

    public function deleteMember(Request $Request, $id)
    {
        $User = $this->User::findOrFail($id);
        $User->user_status_id = UserStatus::INACTIVE;
        $User->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->UserHistory->user_id        = $user->user_id;
        $this->UserHistory->member_id      = $id;
        $this->UserHistory->history_title  = "Member Updated";
        $this->UserHistory->history_note   = "Member has been deleted";
        $this->UserHistory->save();

        return response()->json(['message' => 'Member deleted.']);
    }

    public function changePassword(Request $Request, $id)
    {
        $data = $Request->all();

        $User = $this->User::findOrFail($id);

        if (!Hash::check($data['password'], $User->password)) {
            return response()->json(['passsword' => ['Invalid current password.']], 409);
        }

        $validator = Validator::make($data, [
            'password'     => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 409);
        }

        $User->password = Hash::make($data['new_password']);        
        $User->save();

        $user = JWTAuth::parseToken()->authenticate();
        $this->UserHistory->user_id        = $user->user_id;
        $this->UserHistory->member_id      = $id;
        $this->UserHistory->history_title  = "Member Updated";
        $this->UserHistory->history_note   = "Member Password changed.";
        $this->UserHistory->save();

        return response()->json(['message' => 'success']);
    }
}
