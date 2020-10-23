<?php

use App\Http\Controllers\API\DepositController;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\PaymentHelperController;
use App\Http\Controllers\LoanHelperController;

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

Route::prefix('auth')->group(function () {
    Route::post('/register', [PassportAuthController::class, 'register']);
    Route::post('/login', [PassportAuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::apiResources([
        'loans' => LoanController::class,
        'users' => UserController::class,
        'employees' => EmployeeController::class,
        'deposits' => DepositController::class,
        'payments' => PaymentController::class,
    ]);

    Route::put('loans/status/{id}', [LoanHelperController::class, 'status'])->name("loan.status");
    Route::put('payments/status/{id}', [PaymentHelperController::class, 'status']);
});
