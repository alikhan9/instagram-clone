<?php

namespace App\Http\Controllers;

use App\Events\PostCommentSent;
use App\Models\CommentResponse;
use App\Models\PostComment;
use App\Models\User;
use App\Models\UserMention;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentsController extends Controller
{
    public function storeComment(Request $request)
    {

        $values = $request->validate([
            'post_id' => 'required|numeric|exists:posts,id',
            'content' => 'required|string|max:255',
        ]);

        $string = $values->content;
        $pattern = '/@(\w+)/'; // Regular expression to match '@' followed by word characters

        preg_match_all($pattern, $string, $matches);

        // $matches[1] will contain the captured usernames
        $usernames = $matches[1];

        foreach ($usernames as $username) {
            if (User::where('username', $username)->exists())
                UserMention::create([
                    'post_id' => $values->post_id,
                    'user_id' => User::where('username', $username)->first()->id,
                ]);
        }

        $comment = PostComment::create([
            'post_id' => $values->post_id,
            'user_id' => auth()->id(),
            'content' => $values->content
        ]);

        event(new PostCommentSent($comment));

        return PostComment::find($comment->id);

    }

    public function storeResponse(Request $request)
    {
        $values = $request->validate([
            'post_comment_id' => 'required|numeric|exists:post_comments,id',
            'content' => 'required|string|max:255',
            'user_id' => 'required|numeric|exists:users,id',
        ]);

//        $string = $request->content;
//        $pattern = '/@(\w+)/'; // Regular expression to match '@' followed by word characters
//
//        preg_match_all($pattern, $string, $matches);
//
//        // $matches[1] will contain the captured usernames
//        $usernames = $matches[1];
//
//        foreach ($usernames as $username) {
//            if (User::where('username', $username)->exists())
//                UserMention::create([
//                    'post_id' => $request->post_id,
//                    'user_id' => User::where('username', $username)->first()->id,
//                ]);
//        }

        $result = CommentResponse::create($values);
        $commentResponse = CommentResponse::with('postComment')->where('id', $result->id);

        event(new PostCommentSent($commentResponse->get()->first(), true));
    }

}
