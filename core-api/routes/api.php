<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/pay', [PaymentController::class, 'store']);


Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
Route::get('/wallet/balance', [WalletController::class, 'getBalance']);
