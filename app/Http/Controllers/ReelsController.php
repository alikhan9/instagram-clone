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
        $values = $request->validate([
           'followed' => 'nullable|boolean',
            'notFirst' => 'nullable|boolean',
            'sC' => 'nullable|boolean',
        ]);
        if($values->followed) {
            if($post->id) {
                $posts = Post::where('id', '!=', $post->id)->where('image', null)->whereIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            } else {
                $posts = Post::where('image', null)->whereIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            }
        } else {
            if($post->id) {
                $posts = Post::where('image', null)->whereNotIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->where('id', '!=', $post->id)->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            } else {
                $posts = Post::where('image', null)->whereNotIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(3, ['*'], 'p');
            }
        }

        $followed = filter_var($values->followed, FILTER_VALIDATE_BOOLEAN);

        $posts->getCollection()->transform(function ($post) {
            $post->userLiked = $post->userLiked();
            $post->numberOfComments = $post->comments()->count();
            $post->numberOfLikes = $post->likes()->count();
            $post->comments = [];
            $post->userBookmarked = $post->userBookmarked();
            return $post;
        });

        if($post->id && !$values->notFirst) {
            $post['userLiked'] = $post->userLiked();
            $post['numberOfComments'] = $post->comments()->count();
            $post['numberOfLikes'] = $post->likes()->count();
            $post['likes'] = $post->likes()->count();
            $post['userBookmarked'] = $post->userBookmarked();
            if($values->followed) {
                if(in_array($post->user_id, auth()->user()->following()->pluck('followed')->toArray())) {
                    $posts->prepend($post);
                }
            } else {
                $posts->prepend($post);
            }
        }


        return Inertia::render('Reels', [
            'posts' => $posts,
            'post' => $post,
            'comments' => $values->has('sC') && $values->has('pid') ? Post::find($values->pid)->comments()->paginate(15, ['*'], 'coms')->withQueryString() : null,
            'followed' => $followed,
            'showComments' => (bool)$values->has('sC'),
        ]);
    }
}
