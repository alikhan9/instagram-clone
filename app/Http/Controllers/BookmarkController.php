<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function store(Request $request)
    {
        $values = $request->validate([
            'post_id' => 'numeric|required|exists:posts,id',
            'value' => 'required|boolean'
        ]);
        $bookmark = Bookmark::where('user_id', auth()->id())->where('post_id', $values->post_id);
        if(!$values->value && $bookmark->first()) {
            $bookmark->delete();
            return;
        }

        if(!$bookmark->first()) {
            Bookmark::create([
                'post_id' => $values->post_id,
                'user_id' => auth()->id()
            ]);
        }
    }
}
