<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', function () {
        return Inertia::render('Home', [
            'posts' => DB::table('posts')
            ->orderByDesc('created_at')
            ->limit(4)
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', DB::raw("CONCAT('{ name:', users.name, ', email:', users.email, '}') as user"))
            ->get()
        ]);
    });

    Route::get('/loadMore/{offset}', function ($offset) {
        return DB::table('posts')
        ->offset($offset)
        ->orderByDesc('created_at')
        ->limit(2)
        ->join('users', 'posts.user_id', '=', 'users.id')
        ->select('posts.*', DB::raw("CONCAT('{ name:', users.name, ', email:', users.email, '}') as user"))
        ->get();
    });
    Route::post('/post', [PostController::class, 'store']);
});

require __DIR__.'/auth.php';
