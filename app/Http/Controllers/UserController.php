<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    public function get(Request $request, $username)
    {
        $user = User::where('username', $username)->first();

        $isFollowing = auth()->user()->isFollowing($user);

        if(!$user) {
            return back();
        }

        $posts = null;
        $active = null;
        if($request->value == 'posts' || !$request->value) {
            $posts = $user->posts()->orderByDesc('created_at')->paginate(9);
            $active = 0;
        }

        if($request->value == 'reels') {
            $posts = $user->posts()->where('video', '!=', 'null')->orderByDesc('created_at')->paginate(9);
            $active = 1;
        }
        if($request->value == 'bookmarks') {
            $posts = $user->bookmarks()->orderByDesc('created_at')->paginate(9);
            $active = 2;
        }
        if($request->value == 'mentions') {
            $posts = Post::whereIn('id', $user->mentions()->select('post_id'))->orderByDesc('created_at')->paginate(9);
            $active = 3;
        }

        $followers  = [];
        if(Str::contains($request->path(), 'followers')) {
            $followers = $user->followers()->select('users.name', 'users.id', 'users.username')->get()->map(function ($follower) use ($user) {
                // Add a virtual attribute followedByUser to each follower
                $follower->followedByUser = $user->isFollowing($follower);

                return $follower;
            });
        }
        $following  = [];
        if(Str::contains($request->path(), 'following')) {
            $following = $user->following()->select('users.name', 'users.id', 'users.username')->get()->map(function ($followingUser) use ($user) {
                // Add a virtual attribute followingUser to each user
                $followingUser->followedByUser = true;
                return $followingUser;
            });
        }

        $user->followers()->count();

        return Inertia::render('User', [
            'user' => $user,
            'posts' => $posts,
            'total_posts' => $user->posts()->count(),
            'active' => $active,
            'isFollowing' => $isFollowing,
            'followersCount' => $user->followers()->count(),
            'followingCount' => $user->following()->count(),
            'openFollowers' => Str::contains($request->path(), 'followers'),
            'openFollowing' => Str::contains($request->path(), 'following'),
            'followers' => $followers,
            'following' => $following
        ]);
    }

    public function search($username)
    {
        return User::select('id', 'name', 'username')->where(DB::raw('LOWER(username)'), 'like', '%' . strtolower($username) . '%')->get()->map(function ($user) {
            $user->followersCount = $user->followers()->count();
            return $user;
        });

    }
}
