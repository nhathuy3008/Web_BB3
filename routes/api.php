<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;

Route::post('/accounts/create', [AccountController::class, 'create']);
Route::get('/accounts', [AccountController::class, 'getAllAccounts']);
Route::get('/accounts/{id}', [AccountController::class, 'getAccountById']);
Route::put('/accounts/{id}', [AccountController::class, 'updateAccount']);
Route::delete('/accounts/{id}', [AccountController::class, 'deleteAccount']);
Route::post('/accounts/login', [AccountController::class, 'login']);
Route::post('/accounts/validate-password', [AccountController::class, 'validatePassword']);
Route::get('/accounts/total', [AccountController::class, 'getTotalUsers']);

// Route xác nhận tài khoản
Route::get('/accounts/confirm/{token}', [AccountController::class, 'confirmAccount']);

Route::post('categories/create', [CategoryController::class, 'store']); // Sử dụng POST cho tạo mới
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);

Route::post('/comments', [CommentController::class, 'addComment']);
Route::get('/products/{productId}/comments', [CommentController::class, 'getCommentsByProduct']);

use App\Http\Controllers\CartController;
    Route::get('/cart', [CartController::class, 'getCartItems']);
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart']);
