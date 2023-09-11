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
        return Inertia::render('Home', [
            'posts' => Post::orderByDesc('created_at')->paginate(1)
        ]);
    }

    public function store(Request $request)
    {
        $post = $request->validate([
            'description' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png',
            'video' => 'nullable|file|mimes:mp4,mov,avi',
            'enable_comments' => 'required|boolean',
            'enable_likes' => 'required|boolean',
            'image_description' => 'nullable|string|max:255'
        ]);

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = uniqid('video_') . '.' . $video->getClientOriginalExtension();
            $path = Storage::disk('public')->put('videos/' . $videoName, $video);
            $post['video'] = '/storage/' . $path;
            $post['image'] = null;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = uniqid() . '.webp';

            $optimizedImageBig = Image::make($image)->fit(1400, 1080)->encode('webp', 60);
            $optimizedImageMedium = Image::make($image)->fit(550, 468)->encode('webp', 80);
            $optimizedImageMini = Image::make($image)->fit(309, 309)->encode('webp', 80);

            // Save the optimized images
            Storage::disk('public')->put('images/big_' . $filename, $optimizedImageBig);
            Storage::disk('public')->put('images/medium_' . $filename, $optimizedImageMedium);
            Storage::disk('public')->put('images/small_' . $filename, $optimizedImageMini);

            $post['image'] = '/storage/images/medium_' . $filename;
        }

        $post['user_id'] = auth()->id();
        Post::create($post);
        return redirect('/');
    }
}
