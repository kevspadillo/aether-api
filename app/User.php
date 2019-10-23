<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;
use App\Models\Role;

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
        'role_id',
        'user_status_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'user_status_id', 'role_id'
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
                'users.*',
                'users.user_id', 
                'users.firstname', 
                'users.middlename', 
                'users.lastname', 
                'users.gender', 
                'users.civil_status', 
                'users.tin_number', 
                'users.sss_number', 
                'users.email', 
                'roles.*', 
                'user_statuses.*',
                DB::raw('1 as profile_status'),
                DB::raw('1 as seminar_status'),
                DB::raw('1 as assessment_status')
            )
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->join('user_statuses', 'users.user_status_id', '=', 'user_statuses.user_status_id')
            ->get();
    }

    public function getMembers($memberIds)
    {

        return DB::table('users')
            ->select(
                'users.*',
                'users.user_id', 
                'users.firstname', 
                'users.middlename', 
                'users.lastname', 
                'users.gender', 
                'users.civil_status', 
                'users.tin_number', 
                'users.sss_number', 
                'users.email', 
                'roles.*', 
                'user_statuses.*',
                DB::raw('1 as profile_status'),
                DB::raw('1 as seminar_status'),
                DB::raw('1 as assessment_status')
            )
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('user_statuses', 'users.user_status_id', '=', 'user_statuses.user_status_id')
            ->whereIn('users.user_id', $memberIds)
            ->get();
    }

    public function loans()
    {
        return $this->hasMany('App\Models\Loan', 'user_id', 'user_id')->where('is_deleted', '=', 0);
    }

    public function loanTransactions()
    {
        return $this->hasMany('App\Models\MemberTransactions', 'user_id', 'user_id')->where('transaction_type','=', 'LOAN');
    }

    public function getUserByIdAndPassword($userId, $password)
    {
        return DB::table('users')
            ->where('user_id', '=', $userId)
            ->where('password', '=', $password)
            ->get();
    }

    public function getActiveMembersExclude($excludeUserId)
    {
        $query = DB::table('users')
            ->select(
                'users.user_id', 
                DB::raw('CONCAT(users.firstname, " ", users.lastname) AS "user_name"')
            )
            ->whereNotIn('users.user_id', [$excludeUserId])
            ->where('users.role_id', '=', Role::MEMBER);

        return $query->get();   
    }
}
