<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\QuoteController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\ChatController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\AdminSettingsController;

use App\Http\Controllers\Web\AdminOrderController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminQuoteController;
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
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/products/{product}/add-to-cart', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    
    // Order Flow Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Screen 1: Cart Review
    Route::get('/orders/cart-review', [OrderController::class, 'cartReview'])->name('orders.cart-review');
    
    // Screen 2: Delivery Info
    Route::get('/orders/delivery-info', [OrderController::class, 'deliveryInfo'])->name('orders.delivery-info');
    Route::post('/orders', [OrderController::class, 'create'])->name('orders.create');
    
    // Screen 3: Order Placed Success
    Route::get('/orders/{order}/placed-success', [OrderController::class, 'placedSuccess'])->name('customer.orders.placed-success');
    
    // Screen 4: Quote Sent
    Route::get('/orders/{order}/quote-sent', [OrderController::class, 'quoteSent'])->name('customer.orders.quote-sent');
    
    // Screen 5A: Order Confirmed
    Route::get('/orders/{order}/confirmed', [OrderController::class, 'confirmed'])->name('customer.orders.confirmed');
    
    // Screen 5B: Quote Rejected
    Route::get('/orders/{order}/quote-rejected', [OrderController::class, 'quoteRejected'])->name('customer.orders.quote-rejected');
    
    // Quote Actions
    Route::post('/orders/{order}/quotes/{quote}/accept', [OrderController::class, 'acceptQuote'])->name('customer.orders.acceptQuote');
    Route::post('/orders/{order}/quotes/{quote}/reject', [OrderController::class, 'rejectQuote'])->name('customer.orders.rejectQuote');
    
    // Order Status Check (for AJAX)
    Route::get('/orders/{order}/status-check', [OrderController::class, 'statusCheck'])->name('orders.statusCheck');
    
    // Messages
    Route::post('/orders/{order}/messages', [OrderController::class, 'createMessage'])->name('orders.messages.create');
    
    // General Chat Routes (Customer to Admin)
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unreadCount');
    Route::post('/chat/start', [ChatController::class, 'startChatJson'])->name('chat.start.json');
    Route::get('/chat-list', [ChatController::class, 'index'])->name('chat.index');
    

    // Other Chat Routes
    Route::get('/chat/{user}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    
    // Quotes Routes
    Route::post('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    
    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    Route::post('/settings/language', [SettingsController::class, 'updateLanguage'])->name('settings.updateLanguage');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.updateNotifications');
    Route::post('/settings/delete-account', [SettingsController::class, 'deleteAccount'])->name('settings.deleteAccount');
    
    // Show Order Details (Legacy)
    Route::get('/orders/{order}/details', [OrderController::class, 'show'])->name('orders.show');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/**
 * Admin Routes
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    
    // Admin Settings Routes
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [AdminSettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/settings/password', [AdminSettingsController::class, 'changePassword'])->name('settings.changePassword');
    Route::post('/settings/language', [AdminSettingsController::class, 'updateLanguage'])->name('settings.updateLanguage');
    Route::post('/settings/notifications', [AdminSettingsController::class, 'updateNotifications'])->name('settings.updateNotifications');
    
    
    // Order management routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/delivery', [AdminOrderController::class, 'updateDelivery'])->name('orders.updateDelivery');
    Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/notify', [AdminOrderController::class, 'sendNotification'])->name('orders.notify');
    
    // Product management routes
    Route::get('/products', [AdminDashboardController::class, 'products'])->name('products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Quote management routes
    Route::get('/quotes', [AdminQuoteController::class, 'index'])->name('quotes.index');
    Route::get('/quotes/{order}/create', [AdminQuoteController::class, 'create'])->name('quotes.create');
    Route::post('/quotes/{order}', [AdminQuoteController::class, 'store'])->name('quotes.store');
    Route::get('/quotes/{quote}', [AdminQuoteController::class, 'show'])->name('quotes.show');
    Route::get('/quotes/{quote}/edit', [AdminQuoteController::class, 'edit'])->name('quotes.edit');
    Route::put('/quotes/{quote}', [AdminQuoteController::class, 'update'])->name('quotes.update');
    Route::post('/quotes/{quote}/send', [AdminQuoteController::class, 'send'])->name('quotes.send');
    Route::post('/quotes/{quote}/acceptance', [AdminQuoteController::class, 'handleAcceptance'])->name('quotes.handleAcceptance');
    Route::post('/orders/{order}/new-quote', [AdminQuoteController::class, 'requestNewQuote'])->name('quotes.requestNew');
    Route::delete('/quotes/{quote}', [AdminQuoteController::class, 'destroy'])->name('quotes.destroy');
    
    // Admin Chat Routes (Support Conversations)
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unreadCount');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/conversations/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/conversations/{conversation}/messages', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    
    // Delivery Agent management routes
    Route::resource('delivery-agents', \App\Http\Controllers\AdminDeliveryAgentController::class);
    Route::post('/orders/{order}/assign-delivery', [\App\Http\Controllers\AdminDeliveryAgentController::class, 'assignOrder'])->name('orders.assign-delivery');
    Route::post('/orders/{order}/unassign-delivery', [\App\Http\Controllers\AdminDeliveryAgentController::class, 'unassignOrder'])->name('orders.unassign-delivery');
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

