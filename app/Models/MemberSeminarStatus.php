<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class MemberSeminarStatus extends Model
{
    protected $table = 'member_seminar_status';

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'member_seminar_status_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getMemberSeminarStatus($videoId, $memberId)
    {
        $query = DB::table('member_seminar_status');
        $query->join('seminar_videos', 'seminar_videos.seminar_video_id', '=', 'member_seminar_status.seminar_video_id');
        $query->where('member_seminar_status.seminar_video_id', '=', $videoId);
        $query->where('member_seminar_status.user_id', '=', $memberId);
        return $query->first();
    }

    public function getMemberSeminarVideos($memberId)
    {
        $query = DB::table('member_seminar_status');
        $query->where('member_seminar_status.user_id', '=', $memberId);
        return $query->get();
    }
}
