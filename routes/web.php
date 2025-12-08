<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/* -------------------------------
   PUBLIC OR LOGIN-FIRST HOME
--------------------------------*/
Route::get('/', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');


Route::get('/product/{id}', [ProductController::class, 'show'])
    ->name('product.show');


/* -------------------------------
   AUTH REQUIRED ROUTES
--------------------------------*/
Route::middleware(['auth'])->group(function () {

    // Account
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');


    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/start/{product}', [ChatController::class, 'start'])->name('chat.start');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    // Listings page (seller dashboard)
    Route::get('/listings', [ListingsController::class, 'index'])->name('listings');

    // Wishlist
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])
        ->name('wishlist.add');

    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])
        ->name('wishlist.remove');


    // Profile (breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // product listing
    Route::post('/listings/store', [ListingsController::class, 'store'])->name('listings.store');
    Route::patch('/listings/{product}/update', [ListingsController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{product}/delete', [ListingsController::class, 'destroy'])->name('listings.delete');
});


/* -------------------------------
   VERIFIED EMAIL REQUIRED ROUTES
--------------------------------*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Checkout (only verified users should be able to order)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

    // Admin Dashboard
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});


/* -------------------------------
   PUBLIC ROUTES (no login needed)
--------------------------------*/
Route::get('/category', [CategoryController::class, 'index'])->name('category');

Route::get('/about', function () {
    return view('user.about');
})->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');


require __DIR__.'/auth.php';
