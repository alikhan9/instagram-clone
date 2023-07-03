<?php

namespace App\Http\Controllers;

use App\Events\CommentLikeSent;
use App\Models\CommentLike;
use App\Models\CommentResponse;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\ResponseLike;
use App\Notifications\PostLikeNotification;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likePost(Post $post, Request $request)
    {
        $exists = $post->likes()->wherePivot('user_id', auth()->id())->exists();
        if($exists == $request->value) {
            return;
        }

        $result = $post->likes()->toggle(auth()->id(), );
        if(count($result['attached'])) {
            $post->user()->get()->first()->notify(new PostLikeNotification($post));
        }
    }
    public function likeComment(Request $request, PostComment $comment)
    {

        $like = CommentLike::where('user_id', auth()->id())->where('post_comment_id', $comment->id);
        if($like->get()->count()>0) {
            event(new CommentLikeSent($like->get()->first(), $request->postId,false));
            $like->delete();
            return;
        }

        $commentLike = CommentLike::create([
            'post_comment_id' => $comment->id,
            'user_id' => auth()->id(),
        ]);

        event(new CommentLikeSent($commentLike, $request->postId,true));
    }
    public function likeResponse(Request $request, CommentResponse $response)
    {

        $like = ResponseLike::where('user_id', auth()->id())->where('comment_response_id', $response->id);
        if($like->get()->count()>0) {
            event(new CommentLikeSent($like->get()->first(), $request->postId,false,true,$request->commentId));
            $like->delete();
            return;
        }

        $commentLike = ResponseLike::create([
            'comment_response_id' => $response->id,
            'user_id' => auth()->id(),
        ]);

        event(new CommentLikeSent($commentLike, $request->postId,true,true,$request->commentId));
    }
}
