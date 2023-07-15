<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Inertia\Inertia;

class UserController extends Controller
{
    public function get($username)
    {
        $user = User::where('username', $username)->first();

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

    public function search($username)
    {
        return User::where(DB::raw('LOWER(username)'), 'like', '%' . strtolower($username) . '%')->get();

    }
}
