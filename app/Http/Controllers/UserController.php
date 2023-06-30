<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function get($name)
    {
        $user = User::where('name', $name)->get()->first();

        $posts = $user->posts()->orderByDesc('created_at')->paginate(9);

        foreach ($posts as $post) {
            $post->updated_created_at = $post->created_at->diffForHumans();
            foreach($post->comments as $comment) {
                $comment->updated_created_at = $comment->created_at->diffForHumans();
            }
        }

        return Inertia::render('User', [
            'user' => $user,
            'posts' => $posts,
            'total_posts' => $user->posts()->count()
        ]);
    }
}
