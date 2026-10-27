<?php

use App\Http\Controllers\Admin\AdminComplaintController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::view('/about', 'user.about')->name('about');

// ToyyibPay server-to-server callback: no session, no CSRF (see bootstrap/app.php).
Route::post('/toyyibpay/callback', [PaymentController::class, 'callback'])->name('toyyibpay.callback');

/*
|--------------------------------------------------------------------------
| Signed-in, verified, active members
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active', 'verified'])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/browse', [CategoryController::class, 'index'])->name('browse');
    Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

    Route::post('/location', [LocationController::class, 'update'])->name('location.update');
    Route::delete('/location', [LocationController::class, 'destroy'])->name('location.clear');

    // Cart & checkout
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/item/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/buy-now/{product}', [CheckoutController::class, 'buyNow'])->name('buy.now');

    // Buyer orders & payment
    Route::get('/orders', [OrderController::class, 'index'])->name('buyer.orders');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::patch('/orders/{order}/received', [OrderController::class, 'markAsReceived'])->name('buyer.orders.received');
    Route::get('/payment/return', [PaymentController::class, 'return'])->name('payment.return');

    // Wishlist
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Chat
    Route::get('/chat/{conversation?}', [ChatController::class, 'index'])->name('chat.show');
    Route::post('/chat/start/{product}', [ChatController::class, 'start'])->name('chat.start');
    Route::post('/chat/{conversation}/messages', [ChatController::class, 'send'])->name('chat.send')->middleware('throttle:30,1');
    Route::get('/chat/{conversation}/messages', [ChatController::class, 'poll'])->name('chat.poll');

    // Account
    Route::get('/account', [ProfileController::class, 'show'])->name('account');
    Route::patch('/account', [ProfileController::class, 'update'])->name('profile.update');

    // Contact / complaints
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');

    // Seller centre (UiTM members only)
    Route::middleware('can:sell')->prefix('seller')->group(function () {
        Route::get('/listings', [ListingsController::class, 'index'])->name('listings');
        Route::post('/listings', [ListingsController::class, 'store'])->name('listings.store');
        Route::patch('/listings/{product}', [ListingsController::class, 'update'])->name('listings.update');
        Route::patch('/listings/{product}/status', [ListingsController::class, 'updateStatus'])->name('listings.updateStatus');
        Route::delete('/listings/{product}', [ListingsController::class, 'destroy'])->name('listings.delete');

        Route::get('/orders', [SellerOrderController::class, 'index'])->name('seller.orders');
        Route::patch('/orders/{order}/ship', [SellerOrderController::class, 'ship'])->name('seller.orders.ship');
    });
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->group(function () {
    Route::redirect('/', '/admin/users')->name('admin.dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');

    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions');

    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('admin.complaints');
    Route::patch('/complaints/{complaint}/toggle', [AdminComplaintController::class, 'toggleStatus'])->name('admin.complaints.toggle');
});

require __DIR__.'/auth.php';
