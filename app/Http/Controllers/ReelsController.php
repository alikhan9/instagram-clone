<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReelsController extends Controller
{
    public function index(Post $post, Request $request)
    {
        if($request->followed) {
            if($post->id) {
                $posts = Post::where('id', '!=', $post->id)->where('image', null)->whereIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3);
            } else {
                $posts = Post::where('image', null)->whereIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3);
            }
        } else {
            if($post->id) {
                $posts = Post::where('image', null)->whereNotIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->where('id', '!=', $post->id)->orderByDesc('created_at')->paginate(3);
            } else {
                $posts = Post::where('image', null)->whereNotIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3);
            }
        }

        $followed = filter_var($request->followed, FILTER_VALIDATE_BOOLEAN);

        $posts->getCollection()->transform(function ($post) {
            $post->userLiked = $post->userLiked();
            return $post;
        });

        if($post->id && !$request->notFirst) {
            $post->userLiked = $post->userLiked();
            if($request->followed) {
                if(in_array($post->user_id, auth()->user()->following()->pluck('followed')->toArray())) {
                    $posts->prepend($post);
                }
            } else {
                $posts->prepend($post);
            }
        }

        // dd($posts->getCollection());

        return Inertia::render('Reels', [
            'posts' => $posts,
            'followed' => $followed
        ]);
    }
}
