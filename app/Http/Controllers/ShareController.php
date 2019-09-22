<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Share;
use App\Models\StatusLookup;
use App\Helpers\MemberHelper;
use JWTAuth;
use App\Http\Requests\ShareRequest;

class ShareController extends Controller
{
    protected $Share;

    public function __construct(Share $Share)
    {
        $this->Share = $Share;
    }

    public function index()
    {
        return response()->json(['data' => $this->Share->getAllShares()]);
    }

    public function store(ShareRequest $ShareRequest)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $validated = $ShareRequest->validated();

        $this->Share->user_id          = $user->user_id;
        $this->Share->reference_code   = MemberHelper::generateReferenceCode('shares');
        $this->Share->number_of_shares = $validated['number_of_shares'];
        $this->Share->rate_per_share   = Share::DEFAULT_RATE_PER_SHARE;
        $this->Share->term_id          = 1;
        $this->Share->payment_date     = date('Y-m-d', strtotime($validated['payment_date']));
        $this->Share->status_id        = StatusLookup::PENDING;
        $this->Share->save();

        return response()->json(['data' => ['message' => 'success']]);
    }

    public function update(ShareRequest $ShareRequest, $id)
    {
        $Share = Share::findOrFail($id);
        
        $data = $ShareRequest->validated();

        $Share->number_of_shares = $data['number_of_shares'];
        $Share->payment_date     = $data['payment_date'];
        $Share->status_id        = $data['status_id'];
        $Share->save();

        return response()->json(['data' => ['message' => 'success']]);
    }
}
