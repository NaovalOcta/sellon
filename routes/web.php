<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;

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



// ---------------------------------|| PRODUCT ROUTES ||---------------------------------
// [ GET ] show create product form (PROTECTED)
Route::get('/products/create', [ProductController::class, 'create'])->name('product.create')->middleware('auth');
// [ POST ] product (PROTECTED)
Route::post('/products/form', [ProductController::class, 'store'])->name('product.post')->middleware('auth'); 
// [ GET ALL ] product into selected view
Route::get('/products/view/{view_type}', [ProductController::class, 'index'])->name('product.index');
// [ GET ALL ] product into catalog view
Route::get('/products/catalog', [ProductController::class, 'catalog'])->name('product.catalog'); 
// [ GET ] single product to show detail
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show')->whereNumber('id'); 
// [ GET ] single product to fill edit form
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit')->whereNumber('id'); 
// [ UPDATE ] product
Route::put('/products/{id}', [ProductController::class, 'update'])->name('product.update')->whereNumber('id'); 
// [ DELETE ] product
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy')->whereNumber('id'); 



// ---------------------------------|| USER ROUTES ||---------------------------------
// [ GET ] Current Sign in Use Profile or another user's profile
Route::get('/profile/{id?}', [UserController::class, 'profile'])->name('users.profile'); 
// [ GET ] Current Sign in Use Profile or another user's profile
Route::get('/profile/{id}/edit', [UserController::class, 'editProfile'])->name('users.edit_profile')->middleware('auth'); 
// [ PUT ] Current Sign in Use Profile or another user's profile
Route::put('/profile/{id}', [UserController::class, 'updateProfile'])->name('users.update_profile')->middleware('auth'); 
// [ GET ALL ] my products
Route::get('/users/my-products', [ProductController::class, 'myProducts'])->name('users.my-products')->middleware('auth'); 



// >> FAVORITE ROUTES

// [ POST ] toggle favorite
Route::post('/favorite/toggle/{product_id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle')->whereNumber('product_id')->middleware('auth');
// [ GET ] my favorites
Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorite.index')->middleware('auth');
