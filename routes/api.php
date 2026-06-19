<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PaymentRequestController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    Route::get('/payment-requests', [PaymentRequestController::class, 'index'])->name('api.payment-requests.index');
    Route::post('/payment-requests', [PaymentRequestController::class, 'store'])->name('api.payment-requests.store');
    Route::get('/payment-requests/{payment_request}', [PaymentRequestController::class, 'show'])->name('api.payment-requests.show');
    Route::patch('/payment-requests/{payment_request}/approve', [PaymentRequestController::class, 'approve'])->name('api.payment-requests.approve');
    Route::patch('/payment-requests/{payment_request}/reject', [PaymentRequestController::class, 'reject'])->name('api.payment-requests.reject');
});
