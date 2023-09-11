<?php

namespace App\Http\Controllers;

use App\Events\PostCommentSent;
use App\Models\CommentResponse;
use App\Models\PostComment;
use App\Models\UserMention;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function storeComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
            'content' => 'required'
        ]);

        $string = $request->content;
        $pattern = '/@(\w+)/'; // Regular expression to match '@' followed by word characters

        preg_match_all($pattern, $string, $matches);

        // $matches[1] will contain the captured usernames
        $usernames = $matches[1];

        foreach($usernames as $username) {
            UserMention::create([
                'post_id' => $request->post_id,
                'user_id' => auth()->id()
            ]);
        }

        $comment = PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        event(new PostCommentSent($comment));

        return PostComment::find($comment->id);

    }
    public function storeResponse(Request $request)
    {
        $response = $request->validate([
            'post_comment_id' => 'required',
            'content' => 'required',
            'user_id' => 'required',
        ]);

        $result = CommentResponse::create($response);
        $commentResponse = CommentResponse::with('postComment')->where('id', $result->id);

        event(new PostCommentSent($commentResponse->get()->first(), true));
    }

}
