<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReelsController extends Controller
{
    public function index(Post $post, Request $request)
    {
        $posts = null;
        $request->validate([
            'followed' => 'nullable|string|in:true,false',
            'notFirst' => 'nullable|string|in:true,false',
            'sC' => 'nullable|string|in:true,false',
            'pid' => 'nullable|numeric|exists:posts,id',
        ]);
        if ($request->followed) {
            if ($post->id) {
                $posts = Post::where('id', '!=', $post->id)->where('image', null)->whereIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            } else {
                $posts = Post::where('image', null)->whereIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            }
        } else {
            if ($post->id) {
                $posts = Post::where('image', null)->whereNotIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->where('id', '!=', $post->id)->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            } else {
                $posts = Post::where('image', null)->whereNotIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            }
        }

        $followed = filter_var($request->followed, FILTER_VALIDATE_BOOLEAN);

        $posts->getCollection()->transform(function ($post) {
            $post->userLiked = $post->userLiked();
            $post->numberOfComments = $post->comments()->count();
            $post->numberOfLikes = $post->likes()->count();
            $post->comments = [];
            $post->userBookmarked = $post->userBookmarked();
            return $post;
        });

        if ($post->id && !$request->notFirst) {
            $post['userLiked'] = $post->userLiked();
            $post['numberOfComments'] = $post->comments()->count();
            $post['numberOfLikes'] = $post->likes()->count();
            $post['likes'] = $post->likes()->count();
            $post['userBookmarked'] = $post->userBookmarked();
            if ($request->followed) {
                if (in_array($post->user_id, auth()->user()->following()->pluck('followed')->toArray())) {
                    $posts->prepend($post);
                }
            } else {
                $posts->prepend($post);
            }
        }


        return Inertia::render('Reels', [
            'posts' => $posts,
            'post' => $post,
            'comments' => $request->has('sC') && $request->has('pid') ? Post::find($request->pid)->comments()->paginate(15, ['*'], 'coms')->withQueryString() : null,
            'followed' => $followed,
            'showComments' => (bool)$request->has('sC'),
        ]);
    }
}
