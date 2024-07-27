<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function user(User $receiver)
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

        $groups = auth()->user()->groups;


        return Inertia::render('Direct', [
            'receiver' => count($receiver->getAttributes()) !== 0 ? $receiver : null,
            'groups' => $groups,
            'group' => null,
            'contacts' => auth()->user()->contacts(),
            'messages' => $messages
        ]);
    }


    public function store(Request $request)
    {


        $values = $request->validate([
            'receiver' => 'required|numeric|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $values['sender'] = auth()->id();

        $message =  Message::create($values);

        event(new MessageSent($message));

        return $message;
    }

    public function notify(Request $request)
    {
        $values = $request->validate([
            'sender' => 'required|numeric|exists:users,id'
        ]);

        auth()->user()->notify(new NewMessageNotification($values->sender));
    }
    public function check(Request $request)
    {
        $values = $request->validate([
            'sender' => 'required|numeric|exists:users,id'
        ]);

        auth()->user()->unreadNotifications()->where('data', '{"sender":' . $values->sender . ',"receiver":' . auth()->id() . '}')->delete();
    }

    public function group(Group $group)
    {
        $messages = [];
        if(count($group->getAttributes()) !== 0) {
            $messages = $group->messages;
        }

        $groups = auth()->user()->groups;


        return Inertia::render('Direct', [
            'group' => count($group->getAttributes()) !== 0 ? $group : null,
            'groups' => $groups,
            'contacts' => auth()->user()->contacts(),
            'messages' => $messages,
            'receiver' => null,
        ]);
    }


}
