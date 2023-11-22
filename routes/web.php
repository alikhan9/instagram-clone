<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReelsController;
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
    Route::get('/profile/{username}', [UserController::class, 'get']);
    Route::get('/profile/{username}/followers', [UserController::class, 'get']);
    Route::get('/profile/{username}/following', [UserController::class, 'get']);
    Route::post('/notifications', [UserController::class, 'checkNotifications']);
    Route::get('/search/{username}', [UserController::class, 'search']);

    Route::post('/bookmark', [BookmarkController::class, 'store']);

    Route::get('/reels', [ReelsController::class,'index']);
    Route::get('/reels/{post}/', [ReelsController::class,'index']);
    Route::post('/follow/{user}', [FollowController::class,'store']);

    Route::get('/comments', [CommentsController::class, 'index']);

});
Broadcast::routes(['middleware' => ['auth:sanctum']]);
require __DIR__.'/auth.php';
