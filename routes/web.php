<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\ListingsController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::get('/category', [CategoryController::class, 'index'])->name('category');

Route::get('/about', function () {
    return view('user.about');
})->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');

/*
|--------------------------------------------------------------------------
| TOYYIBPAY CALLBACK ( MUST BE PUBLIC )
|--------------------------------------------------------------------------
| ToyyibPay server → Laravel
| NO auth, NO verified, NO session
*/
Route::post('/toyyibpay/callback', [PaymentController::class, 'callback'])
    ->name('toyyibpay.callback');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Home (login-first)
    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

    // Product
    Route::get('/product/{id}', [ProductController::class, 'show'])
        ->name('product.show');

    // Account
    Route::get('/account', [AccountController::class, 'index'])
        ->name('account');

    Route::get('/account/orders', [AccountController::class, 'orders'])
        ->name('account.orders');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout (page logic only)
    Route::post('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout');

    // Orders (CREATE ORDER + PAY)
    Route::post('/orders', [OrderController::class, 'store'])
        ->name('orders.store');

    // Payment success page (USER REDIRECT)
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])
        ->name('orders.success');

    // Listings (Seller dashboard)
    Route::get('/listings', [ListingsController::class, 'index'])->name('listings');
    Route::post('/listings/store', [ListingsController::class, 'store'])->name('listings.store');
    Route::patch('/listings/{product}/update', [ListingsController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{product}/delete', [ListingsController::class, 'destroy'])->name('listings.delete');

    // Orders (Seller view)
    Route::get('/seller/orders', [SellerOrderController::class, 'index'])
        ->name('seller.orders');
    Route::patch('/seller/orders/{order}/tracking', [SellerOrderController::class, 'updateTracking'])
        ->name('seller.orders.updateTracking');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/start/{product}', [ChatController::class, 'start'])->name('chat.start');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    // Wishlist
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.add');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.remove');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH FILES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
