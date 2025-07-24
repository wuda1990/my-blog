<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/posts');
});

// 为 posts 资源路由添加 auth 中间件，除了 index 方法
Route::resource('posts', PostController::class)->except(['index'])->middleware('auth');
// 单独定义 index 路由，添加 verified 中间件
Route::get('posts', [PostController::class, 'index'])->name('posts.index')->middleware(['auth', 'verified']);

Route::middleware(['auth'])->group(function () {
    Route::post("comments", [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::put("comments/{comment}", [\App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete("comments/{comment}", [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
