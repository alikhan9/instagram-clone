<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function get($name)
    {
        $user = User::where('name', $name)->get()->first();
        return Inertia::render('User', [
            'user' => $user,
            'posts' => $user->posts()->paginate(9),
            'total_posts' => $user->posts()->count()
        ]);
    }
}
