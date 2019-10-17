<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Role;
use App\Models\RolePermission;

class RoleController extends Controller
{
    private $RolePermission;

    public function __construct(
        RolePermission $RolePermission
    ) {
        $this->RolePermission  = $RolePermission;
    }

    public function index()
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            $permissions = $this->RolePermission->getRolePermissions($role->id)->toArray();
            $role['permissions'] = array_column($permissions, 'permission_id');
        }

        return response()->json($roles);
    }
}
