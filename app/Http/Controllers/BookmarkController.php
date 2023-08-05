<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'numeric|required',
            'value' => 'required|boolean'
        ]);
        $bookmark = Bookmark::where('user_id', auth()->id())->where('post_id', $request->post_id);
        if(!$request->value && $bookmark->first()) {
            $bookmark->delete();
            return;
        }

        if(!$bookmark->first()) {
            Bookmark::create([
                'post_id' => $request->post_id,
                'user_id' => auth()->id()
            ]);
        }
    }
}
