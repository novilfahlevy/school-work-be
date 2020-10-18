<?php

use App\Http\Controllers\Api\v1\DepositController;
use App\Http\Controllers\Api\v1\LoanController;
use App\Http\Controllers\Api\v1\PassportAuthController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\EmployeeController;
use App\Http\Controllers\PaymentController;

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

Route::resource('loan', LoanController::class);
Route::resource('user', UserController::class);
Route::resource('employee', EmployeeController::class);
Route::resource('deposit', DepositController::class);

Route::prefix('payment')->group(function () {
    Route::get('/', [PaymentController::class, 'listOfPayments']);
    Route::get('/{id}', [PaymentController::class, 'detailsOfPayment']);
});
