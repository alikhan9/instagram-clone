<?php

namespace App\Http\Controllers;

use App\Models\CommentLike;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likePost(Post $post)
    {
        $post->likes()->toggle(auth()->id());
        return redirect()->back();
    }
    public function likeComment(Request $request, PostComment $comment)
    {
        $request->validate([
            'comment_like_id' => 'nullable'
        ]);

        $like = CommentLike::where('user_id', auth()->id())->where('post_comment_id', $comment->id);
        if($like->get()->count()>0) {
            $like->delete();
            return;
        }
        CommentLike::create([
            'post_comment_id' => $comment->id,
            'user_id' => auth()->id(),
            'comment_like_id' => $request->comment_like_id
        ]);
    }
}
