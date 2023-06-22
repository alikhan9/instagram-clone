<?php

namespace App\Http\Controllers;

use App\Models\PostComments;
use Illuminate\Http\Request;

class PostCommentsController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'post_id' => 'required',
            'content' => 'required'
        ]);

        PostComments::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

            return redirect()->back();
    }

}