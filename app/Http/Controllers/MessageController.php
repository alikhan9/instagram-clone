<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index(User $receiver)
    {
        if(count($receiver->getAttributes()) !== 0 && !Contact::where('receiver', auth()->id())->where('initiator', $receiver->id)->orWhere('initiator', auth()->id())->where('receiver', $receiver->id)->exists()) {
            Contact::create([
                'initiator' => auth()->id(),
                'receiver' => $receiver->id,
            ]);
        }

        $receiver->offsetUnset('phone');
        $receiver->offsetUnset('email');
        $receiver->offsetUnset('email_verified_at');
        $receiver->offsetUnset('created_at');
        $receiver->offsetUnset('updated_at');

        $messages = [];

        if(count($receiver->getAttributes()) !== 0) {
            $messages = Message::where('receiver', auth()->id())->where('sender', $receiver->id)->orWhere('receiver', $receiver->id)->where('sender', auth()->id())->orderBy('created_at', 'ASC')->get();
        }


        return Inertia::render('Direct', [
            'receiver' => count($receiver->getAttributes()) !== 0 ? $receiver : null,
            'contacts' => auth()->user()->contacts(),
            'messages' => $messages
        ]);
    }

}
