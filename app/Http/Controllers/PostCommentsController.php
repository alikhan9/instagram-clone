<?php

namespace App\Http\Controllers;

use App\Events\PostCommentSent;
use App\Models\PostComment;
use Illuminate\Http\Request;

class PostCommentsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
            'content' => 'required'
        ]);

        $comment = PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);


        event(new PostCommentSent($comment));
        return redirect()->back();
    }

}
