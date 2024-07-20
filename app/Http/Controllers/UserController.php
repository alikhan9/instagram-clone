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

        if (!$user) {
            return abort(404);
        }
        $isFollowing = auth()->user()->isFollowing($user);



        $posts = null;
        $active = null;
        if ($request->value == 'posts' || !$request->value) {
            $posts = $user->posts()->orderByDesc('created_at')->paginate(9);
            $active = 0;
        }

        if ($request->value == 'reels') {
            $posts = $user->posts()->where('video', '!=', 'null')->orderByDesc('created_at')->paginate(9);
            $active = 1;
        }
        if ($request->value == 'bookmarks') {
            $posts = $user->bookmarkedPosts();
            $active = 2;
        }
        if ($request->value == 'mentions') {
            $posts = Post::whereIn('id', $user->mentions($user->id)->select('post_id'))->orderByDesc('created_at')->paginate(9);
            $active = 3;
        }


        $posts->getCollection()->transform(function ($post) {
            $post->userLiked = $post->userLiked();
            $post->numberOfComments = $post->comments()->count();
            $post->numberOfLikes = $post->likes()->count();
            $post->comments = [];
            $post->userBookmarked = $post->userBookmarked();
            return $post;
        });

        $followers = [];
        if (Str::contains($request->path(), 'followers')) {
            $followers = $user->followers()->select('users.name', 'users.id', 'users.username', 'users.avatar')->get()->map(function ($follower) use ($user) {
                // Add a virtual attribute followedByUser to each follower
                $follower->followedByUser = $user->isFollowing($follower);
                return $follower;
            });
        }
        $following = [];
        if (Str::contains($request->path(), 'following')) {
            $following = $user->following()->select('users.name', 'users.id', 'users.username', 'users.avatar')->get()->map(function ($followingUser) use ($user) {
                // Add a virtual attribute followingUser to each user
                $followingUser->followedByUser = true;
                return $followingUser;
            });
        }

        $user->followers()->count();

        $post = null;
        if ($request->has('pid')) {
            $post = Post::find($request->pid);
            $post['userLiked'] = $post->userLiked();
            $post['numberOfComments'] = $post->comments()->count();
            $post['numberOfLikes'] = $post->likes()->count();
            $post['likes'] = $post->likes()->count();
            $post['userBookmarked'] = $post->userBookmarked();
        }

        return Inertia::render('User', [
            'user' => $user,
            'post' => $post,
            'comments' => $request->has('pid') ? Post::find($request->pid)->comments()->paginate(15, ['*'], 'c')->withQueryString() : null,
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
        return User::select(['id', 'name', 'username', 'avatar'])->where('id', "!=", auth()->id())->where(DB::raw('LOWER(username)'), 'like', '%' . strtolower($username) . '%')->limit(50)->get()->map(function ($user) {
            $user->followersCount = $user->followers()->count();
            return $user;
        });
    }

    public function searchSmall($username)
    {
        return User::select(['id', 'name', 'username', 'avatar'])->where(DB::raw('LOWER(username)'), 'like', '%' . strtolower($username) . '%')->limit(20)->get();
    }

    public function checkNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }
}
