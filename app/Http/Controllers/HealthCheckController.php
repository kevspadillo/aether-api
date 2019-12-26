<?php

namespace App\Http\Controllers;

class HealthCheckController extends Controller
{
    public function index()
    {   
        return response()->json(['status' => 'ok']);
    }
}
