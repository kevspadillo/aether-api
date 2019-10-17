<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class RolePermission extends Model
{
    /**
     * The attribute that disabled the timestamps
     * @var boolean
     */
    public $timestamps = false;


    public function getRolePermissions($roleId)
    {
        $query = DB::table('role_permissions');
        $query->select('permission_id');
        $query->where('role_id', '=', $roleId);
        return $query->get();
    }
}
