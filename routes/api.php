<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'RegisterController@register');


Route::post('login', 'LoginController@login');
Route::get('login', 'LoginController@index');
Route::get('auth-check', 'AuthController@check');

Route::resource('users', 'UserController');

Route::put('member/{id}/approve', 'UserController@approveMember');
Route::put('member/{id}/delete', 'UserController@deleteMember');
Route::put('member/{id}/disapprove', 'UserController@disapproveMember');

/**
 * Share Routes
 */

Route::resource('shares', 'ShareController');
Route::resource('member-shares', 'MemberShareController');
Route::get('member-shares/{id}/summary', 'MemberShareController@summary');

Route::resource('import', 'ImportController');
Route::post('save-transaction', 'ImportController@saveTransaction');


Route::resource('share-transactions', 'Admin\ShareTransactionsController');