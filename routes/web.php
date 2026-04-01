<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\QuoteController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProductController;
use Illuminate\Support\Facades\Route;

/**
 * Public Routes
 */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

/**
 * Authentication Routes (Guest)
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/**
 * Authenticated Routes (Customer & Admin)
 */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Quote/Order Routes
    Route::post('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/quotes/{quote}/accept', [OrderController::class, 'acceptQuote'])->name('orders.quote.accept');
    Route::post('/orders/{order}/quotes/{quote}/reject', [OrderController::class, 'rejectQuote'])->name('orders.quote.reject');
    Route::post('/orders/{order}/messages', [OrderController::class, 'createMessage'])->name('orders.messages.create');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/**
 * Admin Routes
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('orders.index');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    
    // Product management routes
    Route::get('/products', [AdminDashboardController::class, 'products'])->name('products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
});

/**
 * Vendor Routes
 */
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [VendorDashboardController::class, 'products'])->name('products.index');
    Route::get('/orders', [VendorDashboardController::class, 'orders'])->name('orders.index');
    Route::get('/analytics', [VendorDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/settings', [VendorDashboardController::class, 'settings'])->name('settings');
});

