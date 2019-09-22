<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'gender',
        'civil_status',
        'tin_number',
        'sss_number',
        'email',
        'password',
        'user_type_id',
        'user_status_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attribute that uniquely identify a user
     * @var string
     */
    public $primaryKey = 'user_id';

    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAllUsers()
    {
        return DB::table('users')
            ->select(
                'users.user_id', 
                'users.firstname', 
                'users.middlename', 
                'users.lastname', 
                'users.gender', 
                'users.civil_status', 
                'users.tin_number', 
                'users.sss_number', 
                'users.email', 
                'user_types.*', 
                'user_statuses.*',
                DB::raw('1 as profile_status'),
                DB::raw('1 as seminar_status'),
                DB::raw('1 as assessment_status'),
            )
            ->join('user_types', 'users.user_type_id', '=', 'user_types.user_type_id')
            ->join('user_statuses', 'users.user_status_id', '=', 'user_statuses.user_status_id')
            ->get();
    }

    public function getMembers($memberIds)
    {

        return DB::table('users')
            ->select(
                'users.user_id', 
                'users.firstname', 
                'users.middlename', 
                'users.lastname', 
                'users.gender', 
                'users.civil_status', 
                'users.tin_number', 
                'users.sss_number', 
                'users.email', 
                'user_types.*', 
                'user_statuses.*',
                DB::raw('1 as profile_status'),
                DB::raw('1 as seminar_status'),
                DB::raw('1 as assessment_status'),
            )
            ->join('user_types', 'users.user_type_id', '=', 'user_types.user_type_id')
            ->join('user_statuses', 'users.user_status_id', '=', 'user_statuses.user_status_id')
            ->whereIn('users.user_id', $memberIds)
            ->get();
    }
}
