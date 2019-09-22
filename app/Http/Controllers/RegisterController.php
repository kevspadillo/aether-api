<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\User;
use App\Models\UserStatus;
use App\Models\UserType;

class RegisterController extends Controller
{

    public function __construct(User $User)
    {
        $this->User = $User;
    }

    public function register(RegisterRequest $Request)
    {
        $validated = $Request->validated();

        $this->User->firstname      = $validated['firstname'];
        $this->User->middlename     = $validated['middlename'];
        $this->User->lastname       = $validated['lastname'];
        $this->User->email          = $validated['email'];
        $this->User->password       = Hash::make($validated['password']);
        $this->User->user_status_id = UserStatus::PENDING;
        $this->User->user_type_id   = UserType::GUEST;

        $this->User->save();

        return response()->json(['message' => 'Register Sucesss']);
    }
}
