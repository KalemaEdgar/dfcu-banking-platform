<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//auth()->loginUsingId(1);

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('login', [LoginController::class, 'signIn'])->name('login');
Route::get('logout', [LoginController::class, 'signOut'])->name('logout');
Route::get('dashboard', [UserAccountController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('accounts', [UserAccountController::class, 'index'])->middleware('auth')->name('accounts.index');
Route::get('accounts/{account}', [UserAccountController::class, 'show'])->middleware('auth')->name('accounts.show');
Route::get('transactions/{accountId}/export/', [UserAccountController::class, 'export']);

// Payment related stuff
Route::get('/payment', [TransactionController::class, 'payment'])->middleware('auth')->name('payments.index');
Route::post('/payment/complete', [TransactionController::class, 'makePayment'])->middleware('auth')->name('payments.create');
