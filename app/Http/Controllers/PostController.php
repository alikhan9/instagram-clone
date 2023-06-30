<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;
use Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('created_at')->paginate(1);

        foreach ($posts as $post) {
            $post->updated_created_at = $post->created_at->diffForHumans();
            foreach($post->comments as $comment) {
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

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Generate a unique filename for the image
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Resize and optimize the image
            $optimizedImageBig = Image::make($image)->fit(836, 836)->encode();
            $optimizedImageMedium = Image::make($image)->fit(550, 468)->encode();
            $optimizedImageMini = Image::make($image)->fit(309, 309)->encode();

            // Save the optimized image
            Storage::disk('public')->put('images/big_' . $filename, $optimizedImageBig);
            Storage::disk('public')->put('images/medium_' . $filename, $optimizedImageMedium);
            Storage::disk('public')->put('images/small_' . $filename, $optimizedImageMini);

            $post['image'] = '/storage/images/medium_' . $filename;
        }

        $post['user_id'] = auth()->user()->id;

        Post::create($post);

        return redirect('/');
    }
}
