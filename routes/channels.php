<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('post-{postId}', function ($user, $postId) {
    return Auth::check();
});


Broadcast::channel('App.Models.User.{userId}', function (User $user, $userId) {
    // Add your authorization logic here
    return Auth::check();
});
