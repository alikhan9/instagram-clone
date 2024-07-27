<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupMessageController extends Controller
{
    public function store(Request $request, Group $group)
    {

         $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $userMessageEscaped = htmlspecialchars($request->message, ENT_QUOTES, 'UTF-8');


        $message = $group->messages()->create([
            'user_id' => auth()->user()->id,
            'message' => $userMessageEscaped
        ]);

        event(new GroupMessageSent($message));

        // TODO: Check if the users of this group are listening,and if not send notification that a new message has been sent

    }
}
