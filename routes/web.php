<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', [PostController::class, 'index']);
    Route::post('/post/comment/{comment}/like', [LikeController::class, 'likeComment']);
    Route::post('/post/comment/response/{response}/like', [LikeController::class, 'likeResponse']);

    Route::post('/post/{post}/like', [LikeController::class, 'likePost']);
    Route::post('/post', [PostController::class, 'store']);
    Route::post('/post/comment/response', [CommentsController::class, 'storeResponse']);
    Route::post('/post/comment', [CommentsController::class, 'storeComment']);
    Route::get('/profile/{name}', [UserController::class, 'get']);
});
Broadcast::routes(['middleware' => ['auth:sanctum']]);
require __DIR__.'/auth.php';
