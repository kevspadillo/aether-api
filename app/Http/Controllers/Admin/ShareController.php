<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Share;
use App\Models\ShareHistory;
use App\Models\StatusLookup;
use App\Helpers\MemberHelper;
use JWTAuth;
use App\Http\Requests\ShareRequest;

class ShareController extends Controller
{
    protected $Share;
    protected $ShareHistory;

    public function __construct(
        Share $Share,
        ShareHistory $ShareHistory
    ) {
        $this->Share = $Share;
        $this->ShareHistory = $ShareHistory;
    }

    public function index()
    {
        return response()->json(['data' => $this->Share->getAllShares()]);
    }

    public function show($id)
    {
        $Share = $this->Share->getShare($id);

        if (!$Share) {
            return response()->json(['message' => 'Share Not Found.'], 404);
        }

        return response()->json(['data' => $Share]);
    }

    public function approve(Request $Request, $id)
    {
        $Share = $this->Share::find($id);
        
        if (!$Share) {
            return response()->json(["message" => "Share Not Found."], 404);
        }

        $user = JWTAuth::parseToken()->authenticate();


        $forInactive = $this->Share->where('user_id', '=', $Share->user_id)
            ->where('status_id', '=', StatusLookup::APPROVED)->get();

        foreach ($forInactive as $forInactiveShare) {
            $forInactiveShare->update(['status_id' => StatusLookup::INACTIVE]);

            $DecactivateShareHistory = new ShareHistory();
            $DecactivateShareHistory->share_id       = $forInactiveShare->share_id;
            $DecactivateShareHistory->user_id        = $user->user_id;
            $DecactivateShareHistory->history_title  = 'Share Updated';
            $DecactivateShareHistory->history_note   = 'Share switched to inactive.';
            $DecactivateShareHistory->save();
        }

        $Share->status_id = StatusLookup::APPROVED;
        $Share->approved_by_id = $user->user_id;
        $Share->save();

        $this->ShareHistory->share_id       = $id;
        $this->ShareHistory->user_id        = $user->user_id;
        $this->ShareHistory->history_title  = 'Share Updated';
        $this->ShareHistory->history_note   = 'Share Approved';
        $this->ShareHistory->save();

        return response()->json(['message' => 'Share approved']);
    }

    public function disapprove(Request $Request, $id)
    {
        $Share = $this->Share::find($id);

        if (!$Share) {
            return response()->json(['message' => 'Share Not Found.'], 404);
        }

        $user = JWTAuth::parseToken()->authenticate();
        
        $Share->status_id = StatusLookup::DECLINED;
        $Share->declined_by_id = $user->user_id;
        $Share->save();

        $this->ShareHistory->share_id       = $id;
        $this->ShareHistory->user_id        = $user->user_id;
        $this->ShareHistory->history_title  = 'Share Updated';
        $this->ShareHistory->history_note   = 'Share Disapproved';
        $this->ShareHistory->save();

        return response()->json(['message' => 'Share disapproved.']);
    }

    public function destroy($id)
    {
        $Share = $this->Share::find($id);

        if (!$Share) {
            return response()->json(['message' => 'Share Not Found.'], 404);
        }

        $Share->is_deleted = 1;
        $Share->save();
        return response()->json(['message' => 'Share deleted.']);
    }
}
