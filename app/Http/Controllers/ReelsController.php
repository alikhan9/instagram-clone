<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReelsController extends Controller
{
    public function index(Request $request)
    {
        if($request->followed) {
            $posts = Post::where('image', null)->whereIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(2);
        } else {
            $posts = Post::where('image', null)->whereNotIn('user_id', auth()->user()->following()->pluck('followed')->toArray())->orderByDesc('created_at')->paginate(2);
        }
        $followed = filter_var($request->followed, FILTER_VALIDATE_BOOLEAN);
        return Inertia::render('Reels', [
            'posts' => $posts,
            'followed' => $followed
        ]);
    }
}
