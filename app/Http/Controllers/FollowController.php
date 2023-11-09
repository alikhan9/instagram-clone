<?php

namespace App\Http\Controllers;

use App\Models\User;

class FollowController extends Controller
{
    public function store(User $user)
    {
        if (auth()->user()->isFollowing($user)) {
            auth()->user()->following()->detach($user);
            return false;
        }
        auth()->user()->following()->attach($user);
        return true;
    }
}
