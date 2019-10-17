<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permission = Permission::all();
        return response()->json($permission);
    }
}
