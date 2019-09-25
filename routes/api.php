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
 * Admin Share Routes
 */
Route::resource('admin/shares', 'Admin\ShareController');
Route::put('admin/shares/{id}/approve', 'Admin\ShareController@approve');
Route::put('admin/shares/{id}/disapprove', 'Admin\ShareController@disapprove');
Route::resource('admin/share-transactions', 'Admin\ShareTransactionsController');

/**
 * Savings Routes
 */
Route::resource('admin/savings', 'Admin\SavingsController');
Route::put('admin/savings/{id}/approve', 'Admin\SavingsController@approve');
Route::put('admin/savings/{id}/disapprove', 'Admin\SavingsController@disapprove');
Route::resource('admin/savings-transactions', 'Admin\SavingsTransactionsController');

/**
 * Member Share Routes
 */
Route::resource('member/shares', 'Member\ShareController');
Route::resource('member/{id}/share-transactions', 'Member\ShareTransactionController');
Route::resource('member/{id}/savings-transactions', 'Member\SavingsTransactionController');
Route::get('member/shares/{id}/summary', 'Member\ShareController@summary');

/**
 * Member Savings Routes
 */
Route::resource('member/savings', 'Member\SavingsController');

// Route::resource('import', 'ImportController');
// Route::post('save-transaction', 'ImportController@saveTransaction');