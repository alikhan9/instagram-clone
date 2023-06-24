<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('created_at')
    ->paginate(1);
    Carbon::setLocale('fr');


    foreach ($posts as $post) {
        $post->updated_created_at = $post->created_at->diffForHumans();
        foreach($post->comments as $comment){
            $comment->updated_created_at = $comment->created_at->diffForHumans();
        }
    }

        return Inertia::render('Home', [
            'posts' => $posts
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