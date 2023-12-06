<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupMessageController extends Controller
{
    public function store(Request $request, Group $group)
    {

        $this->validate($request, [
            'message' => 'required',
        ]);

        $message = $group->messages()->create([
            'user_id' => auth()->user()->id,
            'message' => $request->message
        ]);

        event(new GroupMessageSent($message));


    }
}
