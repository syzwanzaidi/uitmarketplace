<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ListingsController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Show login page if not logged in, otherwise show home
Route::get('/', function () {
    if (Auth::check()) {
        return view('user.home');
    } else {
        return redirect()->route('login');
    }
})->name('home');

// User Account Page
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
});

// Customers Routes

// User Cart Page
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// Category Page
Route::get('/category', [CategoryController::class, 'index'])->name('category');

// Checkout Page
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

// Chat Page
Route::get('/chat', [ChatController::class, 'index'])->name('chat');

// Listings Page
Route::get('/listings', [ListingsController::class, 'index'])->name('listings');


// About Page
Route::get('/about', function () {
    return view('user.about');
})->name('about');

// Contact Page
Route::get('/contact', [ContactController::class, 'index'])->name('contact');



//Admin Routes

// Admin Dashboard
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');


// Profile routes (auto from Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
