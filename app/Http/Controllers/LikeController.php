<?php

namespace App\Http\Controllers;

use App\Models\PostLikes;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request)
    {

        $like = PostLikes::where('user_id', '=', auth()->user()->id)->where('post_id', '=', $request->post_id)->count();
        if($like > 0) {
            $postLike = PostLikes::where('user_id', '=', auth()->user()->id)->where('post_id', '=', $request->post_id)->get()->first();
            $postLike->active = !$postLike->active;
            $postLike->save();
            return back();
        }

        return PostLikes::create([
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id,
        ]);
    }
}
