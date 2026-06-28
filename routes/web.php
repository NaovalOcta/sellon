<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StatsController;

// ---------------------------------|| AUTH ROUTES ||---------------------------------
// [ GET ] landing page (home)
Route::get('/', [ProductController::class, 'index'], )->name('home');
// [ GET ] login page
Route::get('/login', function() {
  return view('auth.login');
})->name('login');
// [ GET ] register page
Route::get('/register', function() {
  return view('auth.register');
})->name('register');
// [ POST ] logout
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
// [ POST ] login
Route::post('/login_post', [UserController::class, 'login'])->name('login_post');
Route::post('/register_post', [UserController::class, 'register'])->name('register_post');



// ---------------------------------|| EMAIL VERIFICATION ROUTES ||---------------------------------
Route::middleware('auth')->group(function () {
  // [ GET ] halaman instruksi verifikasi email
  Route::get('/verify', [VerificationController::class, 'showNotice'])->name('verification.notice');
  // [ POST ] verifikasi OTP dari email
  Route::post('/email/verify', [VerificationController::class, 'verify'])
    ->name('verification.verify');
  // [ POST ] kirim ulang email verifikasi (throttle: 1x per menit)
  Route::post('/verify/resend', [VerificationController::class, 'resend'])
    ->middleware('throttle:1,1')->name('verification.send');
});



// ---------------------------------|| PRODUCT ROUTES ||---------------------------------
// [ GET ] show create product form (PROTECTED: login + email verified)
Route::get('/products/create', [ProductController::class, 'create'])->name('product.create')->middleware(['auth', 'verified']);
// [ POST ] product (PROTECTED: login + email verified)
Route::post('/products/form', [ProductController::class, 'store'])->name('product.post')->middleware(['auth', 'verified']);
// [ GET ALL ] product into selected view
Route::get('/products/view/{view_type}', [ProductController::class, 'index'])->name('product.index');
// [ GET ALL ] product into catalog view
Route::get('/products/catalog', [ProductController::class, 'catalog'])->name('product.catalog');
// [ GET ] single product to show detail
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show')->whereNumber('id');
// [ GET ] single product to fill edit form (PROTECTED: login + email verified)
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit')->whereNumber('id')->middleware(['auth', 'verified']);
// [ UPDATE ] product (PROTECTED: login + email verified)
Route::put('/products/{id}', [ProductController::class, 'update'])->name('product.update')->whereNumber('id')->middleware(['auth', 'verified']);
// [ DELETE ] product (PROTECTED: login + email verified)
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy')->whereNumber('id')->middleware(['auth', 'verified']);



// ---------------------------------|| USER ROUTES ||---------------------------------
// [ GET ] Current Sign in User Profile or another user's profile
Route::get('/profile/{id?}', [UserController::class, 'profile'])->name('users.profile');
// [ GET ] Edit profile (PROTECTED: login only, tidak perlu verified untuk edit profil)
Route::get('/profile/{id}/edit', [UserController::class, 'editProfile'])->name('users.edit_profile')->middleware('auth');
// [ PUT ] Update profile (PROTECTED: login only)
Route::put('/profile/{id}', [UserController::class, 'updateProfile'])->name('users.update_profile')->middleware('auth');
// [ GET ALL ] my products (PROTECTED: login + email verified)
Route::get('/users/my-products', [ProductController::class, 'myProducts'])->name('users.my-products')->middleware(['auth', 'verified']);



// >> FAVORITE ROUTES

// [ POST ] toggle favorite (PROTECTED: login + email verified)
Route::post('/favorite/toggle/{product_id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle')->whereNumber('product_id')->middleware(['auth', 'verified']);
// [ GET ] my favorites (PROTECTED: login + email verified)
Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorite.index')->middleware(['auth', 'verified']);

// PREMIUM SELLER ROUTES
Route::get('/premium', [SubscriptionController::class, 'index'])->name('premium.index');
Route::post('/premium/subscribe', [SubscriptionController::class, 'subscribe'])
    ->middleware(['auth', 'verified'])->name('premium.subscribe');
Route::get('/premium/confirm/{id}', [SubscriptionController::class, 'confirmPayment'])
    ->middleware(['auth', 'verified'])->name('premium.confirm');           // id = subscription_order_id
Route::post('/premium/confirm/{id}', [SubscriptionController::class, 'submitPayment'])
    ->middleware(['auth', 'verified'])->name('premium.submit_payment');
Route::post('/premium/cancel/{id}', [SubscriptionController::class, 'cancel'])
    ->middleware(['auth', 'verified'])->name('premium.cancel');            // id = subscription_order_id

// PROMOTED LISTING ROUTES
Route::get('/promote', [PromotionController::class, 'index'])->name('promote.index');
Route::get('/promote/select', [PromotionController::class, 'create'])
    ->middleware(['auth', 'verified'])->name('promote.create');
Route::post('/promote/order', [PromotionController::class, 'store'])
    ->middleware(['auth', 'verified'])->name('promote.store');
Route::get('/promote/confirm/{id}', [PromotionController::class, 'confirmPayment'])
    ->middleware(['auth', 'verified'])->name('promote.confirm');           // id = promotion_order_id
Route::post('/promote/confirm/{id}', [PromotionController::class, 'submitPayment'])
    ->middleware(['auth', 'verified'])->name('promote.submit_payment');
Route::get('/promote/my-promotions', [PromotionController::class, 'myPromotions'])
    ->middleware(['auth', 'verified'])->name('promote.my');
Route::post('/promote/cancel/{id}', [PromotionController::class, 'cancel'])
    ->middleware(['auth', 'verified'])->name('promote.cancel');            // id = promotion_order_id

// STATS ROUTES (Premium Only)
Route::get('/stats', [StatsController::class, 'index'])
    ->middleware(['auth', 'verified', 'premium'])->name('stats.index');

// ADMIN ROUTES
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/payments', [AdminController::class, 'pendingPayments'])->name('admin.payments');
    Route::post('/payments/{id}/approve', [AdminController::class, 'approvePayment'])->name('admin.payments.approve');
    Route::post('/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('admin.payments.reject');
    Route::get('/payments/history', [AdminController::class, 'paymentHistory'])->name('admin.payments.history');
});

