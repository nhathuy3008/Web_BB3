<?php

use App\Http\Controllers\AccountController;

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

use App\Http\Controllers\CategoryController;
Route::post('categories/create', [CategoryController::class, 'store']); // Sử dụng POST cho tạo mới
Route::apiResource('categories', CategoryController::class);
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);