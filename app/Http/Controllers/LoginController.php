<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\UserStatus;

class LoginController extends Controller
{
    public function login() {
        // get email and password from request
        $credentials = request(['email', 'password']);
        
        // try to auth and get the token using api authentication
        if (!$token = auth('api')->attempt($credentials)) {
            // if the credentials are wrong we send an unauthorized error in json format
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = JWTAuth::setToken($token)->authenticate();

        if ($user->user_status_id == UserStatus::INACTIVE) {
            return response()->json(['error' => 'Account inactive, please contact administrator to reactivate'], 401);
        }

        if ($user->user_status_id == UserStatus::PENDING) {
            return response()->json(['error' => 'Account Pending, please wait for the confirmation for approved account'], 401);
        }

        return response()->json([
            'id'           => 1,
            'username'     => 'admin',
            'password'     => 'demo',
            'email'        => 'kevin.padilla0717@gmailc.com',
            'accessToken'  => $token,
            'refreshToken' => auth('api')->factory()->getTTL() * 60,
            'roles'        => [1],
            'pic'          => './assets/media/users/300_25.jpg',
            'fullname'     => 'Sean',
            'occupation'   => 'CEO',
            'companyName'  => 'Keenthemes',
            'phone'        => '456669067890',
            'address'      => [
                'addressLine' => 'L-12-20 Vertex, Cybersquare',
                'city'        => 'San Francisco',
                'state'       => 'California',
                'postCode'    => '45000'
            ],
            'socialNetworks' => [
                'linkedIn'   => 'https://linkedin.com/admin',
                'facebook'   => 'https://facebook.com/admin',
                'twitter'    => 'https://twitter.com/admin',
                'instagram'  => 'https://instagram.com/admin'
            ]
        ]);
    }

    public function index()
    {
        return response()->json([
            'id'           => 1,
            'username'     => 'admin',
            'password'     => 'demo',
            'email'        => 'kevin.padilla0717@gmailc.com',
            'accessToken'  => 'test',
            'refreshToken' => 'test',
            'roles'        => [1],
            'pic'          => './assets/media/users/300_25.jpg',
            'fullname'     => 'Sean',
            'occupation'   => 'CEO',
            'companyName'  => 'Keenthemes',
            'phone'        => '456669067890',
            'address'      => [
                'addressLine' => 'L-12-20 Vertex, Cybersquare',
                'city'        => 'San Francisco',
                'state'       => 'California',
                'postCode'    => '45000'
            ],
            'socialNetworks' => [
                'linkedIn'   => 'https://linkedin.com/admin',
                'facebook'   => 'https://facebook.com/admin',
                'twitter'    => 'https://twitter.com/admin',
                'instagram'  => 'https://instagram.com/admin'
            ]
        ]);
    }
}
