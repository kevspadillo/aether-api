<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Share;

class MemberShareController extends Controller
{
    protected $Share;

    public function __construct(Share $Share)
    {
        $this->Share = $Share;
    }

    public function show($id)
    {
        return response()->json($this->Share->getMemberShares($id));
    }

    public function summary($id)
    {
        return response()->json(
            [
                'total_shares'    => 10000,
                'total_pending'   => 10000,
                'total_approved'  => 10000,
                'total_declined'  => 10000
            ]
        );
    }
}
