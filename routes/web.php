<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\VerificationController;

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
  // [ GET ] callback link verifikasi dari email (harus signed URL)
  Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware('signed')->name('verification.verify');
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

