<?php

use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\Web\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/payment-requests', [PaymentRequestController::class, 'index'])->name('payment-requests.index');
    Route::post('/payment-requests', [PaymentRequestController::class, 'store'])->name('payment-requests.store');

    Route::middleware('can:finance,'.User::class)->group(function () {
        Route::get('/payment-requests/approval', [PaymentRequestController::class, 'approvalIndex'])->name('payment-requests.approval');
        Route::post('/payment-requests/{payment_request}/approve', [PaymentRequestController::class, 'approve'])->name('payment-requests.approve');
        Route::post('/payment-requests/{payment_request}/reject', [PaymentRequestController::class, 'reject'])->name('payment-requests.reject');
    });
});
