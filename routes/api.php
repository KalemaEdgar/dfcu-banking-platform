<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('auth.login');
// Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::get('/accounts', [AccountController::class, 'index']);

Route::prefix('/accounts')->group(function () {
    Route::get('/', [AccountController::class, 'index']);
    Route::get('/active', [AccountController::class, 'active']);
    Route::get('/blocked', [AccountController::class, 'blocked']);
    Route::post('/user', [AccountController::class, 'showUserAccounts']);
    Route::get('/{account_number}', [AccountController::class, 'show']);
});

Route::post('/payment', [TransactionController::class, 'makePayment']);

Route::prefix('/payments')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/account/{accountNumber}', [TransactionController::class, 'showAccountTxns']);
    Route::get('/{referenceNumber}', [TransactionController::class, 'show']);
});
