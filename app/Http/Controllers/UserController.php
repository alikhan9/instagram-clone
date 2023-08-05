<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function get(Request $request, $username)
    {
        $user = User::where('username', $username)->first();

        if(!$user) {
            return back();
        }

        $posts = null;
        $active = null;
        if($request->value == 'posts' || !$request->value) {
            $posts = $user->posts()->orderByDesc('created_at')->paginate(9);
            $active=0;
        }

        if($request->value == 'bookmarks') {
            $posts = $user->bookmarks()->orderByDesc('created_at')->paginate(9);
            $active=2;
        }

        return Inertia::render('User', [
            'user' => $user,
            'posts' => $posts,
            'total_posts' => $user->posts()->count(),
            'active' => $active
        ]);
    }

    public function search($username)
    {
        return User::where(DB::raw('LOWER(username)'), 'like', '%' . strtolower($username) . '%')->get();

    }
}
