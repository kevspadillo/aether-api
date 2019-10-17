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

Route::post('register', 'RegisterController@register');

Route::post('login', 'LoginController@login');
Route::get('login', 'LoginController@index');

Route::resource('roles', 'RoleController');
Route::resource('permissions', 'PermissionController');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::resource('users', 'UserController');
    Route::put('change-password/{id}', 'UserController@changePassword');

    Route::get('auth-check', 'AuthController@check');


    Route::put('member/{id}/approve', 'UserController@approveMember');
    Route::put('member/{id}/delete', 'UserController@deleteMember');
    Route::put('member/{id}/disapprove', 'UserController@disapproveMember');

    Route::post('loans/calculate-loan', 'LoanCalculatorController@calculateLoan');

    Route::prefix('dashboard')->group(function() {
        Route::get('membership', 'Admin\DashboardController@membershipSummary');
        Route::get('shares', 'Admin\DashboardController@postedShares');
        Route::get('savings', 'Admin\DashboardController@postedSavings');
        Route::get('loans', 'Admin\DashboardController@postedLoans');
        Route::get('summary', 'Admin\DashboardController@countSummary');
    });

    Route::prefix('admin')->group(function () {

        /**
         * Admin Share Routes
         */
        Route::resource('shares', 'Admin\ShareController');
        Route::put('shares/{id}/approve', 'Admin\ShareController@approve');
        Route::put('shares/{id}/disapprove', 'Admin\ShareController@disapprove');
        Route::resource('share-transactions', 'Admin\ShareTransactionsController');

        /**
         * Admin Savings Routes
         */
        Route::resource('savings', 'Admin\SavingsController');
        Route::put('savings/{id}/approve', 'Admin\SavingsController@approve');
        Route::put('savings/{id}/disapprove', 'Admin\SavingsController@disapprove');
        Route::resource('savings-transactions', 'Admin\SavingsTransactionsController');

        /**
         * Admi Loan Routes
         */
        Route::resource('loans', 'Admin\LoanController');
        Route::put('loans/{loanId}/verify', 'Admin\LoanController@verifyLoan');
        Route::resource('loans-transactions', 'Admin\LoanTransactionsController');
    });


    Route::prefix('member')->group(function () {

        Route::resource('shares', 'Member\ShareController');
        Route::resource('savings', 'Member\SavingsController');
        Route::resource('loans', 'Member\LoanController');

        Route::get('shares/{id}/summary', 'Member\ShareController@summary');

        Route::resource('{id}/share-transactions', 'Member\ShareTransactionController');
        Route::resource('{id}/savings-transactions', 'Member\SavingsTransactionController');
        Route::resource('{id}/loan-transactions', 'Member\LoanTransactionController');

        Route::resource('loan/co-maker', 'Member\LoanCoMakerController');
    });


    /**
     * Transaction Upload
     */
    Route::resource('import', 'ImportController');
    Route::post('save-transaction', 'ImportController@saveTransaction');
});