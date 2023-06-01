<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', function () {
        return Inertia::render('Home', [
            'posts' => ['title'=>'Le titre','description'=>'La description']
        ]);
    });
    Route::post('/post', [PostController::class, 'store']);
});

require __DIR__.'/auth.php';