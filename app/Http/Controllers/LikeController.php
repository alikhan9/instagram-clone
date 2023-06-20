<?php

namespace App\Http\Controllers;

use App\Models\Post;

class LikeController extends Controller
{
    public function store(Post $post)
    {
        $post->likes()->toggle(auth()->id());
        return redirect()->back();
    }
}
