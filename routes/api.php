<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('wallets', [WalletController::class, 'create']);
    Route::post('wallets/transfer', [TransactionController::class, 'transfer']);
    Route::get('wallets/{id}/transactions', [TransactionController::class, 'history']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('role:super-admin')->group(function () {
        // Manage Users
        Route::get('admin/users', [AdminController::class, 'listUsers']);
        Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser']);
        Route::patch('admin/users/{id}/ban', [AdminController::class, 'banUser']);

        // Manage Wallets
        Route::get('admin/wallets', [AdminController::class, 'listWallets']);
        Route::post('admin/wallets', [AdminController::class, 'createWallet']);
        Route::delete('admin/wallets/{id}', [AdminController::class, 'deleteWallet']);

        // View Transaction History
        Route::get('admin/transactions', [AdminController::class, 'allTransactions']);

        // Dashboard
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    });
});
