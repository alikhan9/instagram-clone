<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index(User $receiver)
    {
        if(count($receiver->getAttributes()) !== 0) {
            if(!Contact::where('receiver', auth()->id())->where('initiator', $receiver->id)->orWhere('initiator', auth()->id())->where('receiver', $receiver->id)->exists() && $receiver->id !== auth()->id()) {
                Contact::create([
                    'initiator' => auth()->id(),
                    'receiver' => $receiver->id,
                ]);
            } else {
                $contact = Contact::where('receiver', auth()->id())->where('initiator', $receiver->id)->orWhere('initiator', auth()->id())->where('receiver', $receiver->id)->first();
                $contact->valid = true;
                $contact->save();
            }
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


    public function store(Request $request)
    {

        $result = $request->validate([
            'receiver' => 'required|exists:users,id',
            'message' => 'required|string|max:10000'
        ]);

        $result['sender'] = auth()->id();



        $message =  Message::create($result);

        event(new MessageSent($message));


        return $message;
    }

    public function notify(Request $request)
    {
        $request->validate([
            'sender' => 'required|exists:users,id'
        ]);

        auth()->user()->notify(new NewMessageNotification($request->sender));
    }
    public function check(Request $request)
    {
        $request->validate([
            'sender' => 'required|exists:users,id'
        ]);

        auth()->user()->unreadNotifications()->where('data', '{"sender":' . $request->sender . ',"receiver":' . auth()->id() . '}')->delete();
    }




}
