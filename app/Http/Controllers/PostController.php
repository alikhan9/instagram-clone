<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        // User::findOrNew()
        return Inertia::render('Home', [
            'posts' => Post::orderByDesc('created_at')->paginate(1),
        ]);
    }

    public function store(Request $request)
    {

        $post = $request->validate([
            'description' => 'nullable',
            'location' => 'required',
            'image' => 'required',
            'enable_comments' => 'required',
            'enable_likes' => 'required',
            'image_description' => 'nullable'
        ]);


        $path = $request->file('image')->store('public/images');
        $imagePath = Storage::url($path);

        $post['image'] = $imagePath;
        $post['user_id'] = auth()->user()->id;

        Post::create($post);

        return redirect('/');
    }
}