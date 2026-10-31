<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

// ==========================================
// AUTHENTICATION ROUTES (Đăng ký, Đăng nhập, Quên mật khẩu)
// ==========================================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Bổ sung Route Quên mật khẩu
Route::get('/forgot_password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot_password', [AuthController::class, 'forgotPassword'])->name('password.email');


// ==========================================
// POST ROUTES (Quản lý bài viết)
// ==========================================

// Route danh sách bài viết (Công khai)
Route::get('/post', [PostController::class, 'index'])->name('post.index');

// ĐẶT ROUTE /post/create LÊN TRƯỚC /post/{id}
Route::middleware(['auth'])->group(function () {
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');
});

// Route chi tiết bài viết (Đặt ở cuối vì có tham số động {id})
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');


// ==========================================
// TEST LOG ROUTE
// ==========================================
Route::get('/test-log', function () {
    \Illuminate\Support\Facades\Log::info('Test ghi log theo ngay thành công!');
    return 'Đã ghi log!';
});