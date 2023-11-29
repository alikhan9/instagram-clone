<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{
    public function activate(Contact $contact)
    {
        $contact->valid = true;
        $contact->save();
    }
}
