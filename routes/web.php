<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Checkout Flow
Route::get('/checkout/{package}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{package}', [CheckoutController::class, 'store'])->name('checkout.store');

// Payment Page
Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{order}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');

// Order Status & Success Page
Route::get('/order/{order_number}', [PaymentController::class, 'status'])->name('order.status');

// Protected Admin Portal
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/admin/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.updateStatus');
