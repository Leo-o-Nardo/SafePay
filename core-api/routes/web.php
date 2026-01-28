<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/pay', [DashboardController::class, 'store'])->name('pay.store');
Route::get('/payments/list', [DashboardController::class, 'list'])->name('payments.list'); // Para o AJAX
Route::post('/reset', [DashboardController::class, 'reset'])->name('system.reset'); // <--- Nova rota
Route::post('/replay-dlq', [DashboardController::class, 'replayDlq'])->name('queue.replay');
