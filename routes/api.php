<?php

use App\Http\Controllers\API\DepositController;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BalanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\LoanSubmissionController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PaymentHelperController;
use App\Http\Controllers\LoanHelperController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositHelperController;

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

Route::get('/', function () {
    return 'API Service KSP v1';
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [PassportAuthController::class, 'register']);
    Route::post('/login', [PassportAuthController::class, 'login']);
});

Route::middleware('api')->group(function () {
    // Export
    Route::get('export/{start_date}/{end_date}/{type}', [ExportController::class, 'export']);

    // Resources
    Route::middleware('auth:api')->group(function () {
        Route::apiResources([
            'loans' => LoanController::class,
            'users' => UserController::class,
            'employees' => EmployeeController::class,
            'deposits' => DepositController::class,
            'payments' => PaymentController::class,
            'balances' => BalanceController::class,
            'loan-submissions' => LoanSubmissionController::class,
        ]);

        Route::delete('users/{id}/{type}', [UserController::class, 'destroy']);
        Route::put('loan-submissions/status/{id}', [LoanSubmissionController::class, 'status']);
        Route::put('loans/status/{id}', [LoanHelperController::class, 'status'])->name("loan.status");
        Route::put('deposits/status/{id}', [DepositHelperController::class, 'status'])->name("deposit.status");
        Route::put('payments/status/{id}', [PaymentHelperController::class, 'status']);
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('dashboard/user', [DashboardController::class, 'user']);
        Route::put('password/change', [ProfileController::class, 'changePassword']);
    });
});
