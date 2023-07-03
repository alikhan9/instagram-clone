<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function get($name)
    {
        $user = User::where('name', $name)->get()->first();

        if(!$user) {
            return back();
        }

        $posts = $user->posts()->orderByDesc('created_at')->paginate(9);

        return Inertia::render('User', [
            'user' => $user,
            'posts' => $posts,
            'total_posts' => $user->posts()->count()
        ]);
    }
}
