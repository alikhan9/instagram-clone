<?php

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMessageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReelsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Comment Routes
    Route::post('/post/comment/{comment}/like', [LikeController::class, 'likeComment']);
    Route::post('/post/comment/response/{response}/like', [LikeController::class, 'likeResponse']);
    Route::post('/post/comment', [CommentsController::class, 'storeComment']);
    Route::post('/post/comment/response', [CommentsController::class, 'storeResponse']);
    Route::get('/comments', [CommentsController::class, 'index']);

    // Post Routes
    Route::get('/', [PostController::class, 'index']);
    Route::post('/post', [PostController::class, 'store']);

    // Bookmark Routes
    Route::post('/bookmark', [BookmarkController::class, 'store']);

    // Like Routes
    Route::post('/post/{post}/like', [LikeController::class, 'likePost']);

    // User profile Routes
    Route::get('/profile/{username}', [UserController::class, 'get']);
    Route::get('/profile/{username}/followers', [UserController::class, 'get']);
    Route::get('/profile/{username}/following', [UserController::class, 'get']);

    // Notification Routes
    Route::post('/notifications', [UserController::class, 'checkNotifications']);
    Route::post('/message/notifications/notify', [MessageController::class, 'notify']);
    Route::post('/message/notifications/check', [MessageController::class, 'check']);

    // Search Route
    Route::get('/search/{username}', [UserController::class, 'search']);

    // Message Routes
    Route::get('/direct', [MessageController::class, 'index']);
    Route::get('/direct/t/{receiver}', [MessageController::class, 'user']);
    Route::get('/direct/g/{group}', [MessageController::class, 'group']);
    Route::post('/message', [MessageController::class, 'store']);

    // Contact Routes
    Route::post('/contact/activate/{contact}', [ContactController::class, 'activate']);

    // Reels Routes
    Route::get('/reels', [ReelsController::class,'index']);
    Route::get('/reels/{post}/', [ReelsController::class,'index']);

    // Follow Routes
    Route::post('/follow/{user}', [FollowController::class,'store']);


    // Group Routes
    Route::post('/group', [GroupController::class,'store']);
    Route::delete('/group/{group}', [GroupController::class,'destroy']);
    Route::post('/group/{group}/add', [GroupController::class,'add']);
    Route::post('/group/{group}/remove', [GroupController::class,'remove']);
    Route::put('/group/{group}', [GroupController::class,'update']);

    // Group Message Routes
    Route::post('/group/{group}/message', [GroupMessageController::class,'store']);

});
Broadcast::routes(['middleware' => ['auth:sanctum']]);
require __DIR__.'/auth.php';
