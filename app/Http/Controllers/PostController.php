<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;
use Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::latest()->paginate(3, ['*'], 'p')->through(function ($post) {
            $post->userLiked = $post->userLiked();
            $post->numberOfComments = $post->comments()->count();
            $post->numberOfLikes = $post->likes()->count();
            $post->comments = [];
            return $post;
        })->withQueryString();


        $mostFollowedUsers = User::select(['id', 'name', 'username', 'avatar'])
            ->withCount('following')
            ->whereNotIn('users.id', auth()->user()->following()->select('users.id'))
            ->orderBy('following_count', 'desc')
            ->take(5)
            ->get();

        $post = null;
        if ($request->has('pid')) {
            $post = Post::find($request->pid);
            $post['userLiked'] = $post->userLiked();
            $post['numberOfComments'] = $post->comments()->count();
            $post['numberOfLikes'] = $post->likes()->count();
            $post['likes'] = $post->likes()->count();
            $post['userBookmarked'] = $post->userBookmarked();
        }

        return Inertia::render('Home', [
            'posts' => $posts,
            'mostFollowedUsers' => $mostFollowedUsers,
            'post' => $post,
            'comments' => $request->has('pid') ? Post::find($request->pid)->comments()->paginate(15, ['*'], 'c')->withQueryString() : null,
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
