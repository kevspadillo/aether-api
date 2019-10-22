<?php

namespace App\Http\Controllers;

use Request;
use Str;
use JWTAuth;
use App\Models\UserStatus;

class AuthController extends Controller
{
    public function check() {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        $user = compact('user');

        if ($user['user']->user_status_id == UserStatus::INACTIVE) {
            return response()->json(null);
        }

        $userData = [
            'id'           => $user['user']->user_id,
            'username'     => $user['user']->email,
            'email'        => $user['user']->email,
            'roles'        => [$user['user']->role_id],
            'pic'          => './assets/media/users/default.jpg',
            'fullname'     => $user['user']->firstname . ' ' . $user['user']->lastname,
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
        ];

        return response()->json($userData);
    }
}
