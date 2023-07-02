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
            $filename = uniqid() . '.webp';

            // Resize and optimize the images
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
