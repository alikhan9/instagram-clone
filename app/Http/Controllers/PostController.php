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
        $request->validate([
            'followed' => 'nullable|string|in:true,false',
            'pid' => 'nullable|numeric|exists:posts,id',
        ]);
        if (!$request->followed)
            $posts = Post::latest()->paginate(3, ['*'], 'p')->through(function ($post) {
                $post->userLiked = $post->userLiked();
                $post->numberOfComments = $post->comments()->count();
                $post->numberOfLikes = $post->likes()->count();
                $post->userBookmarked = $post->userBookmarked();
                $post->comments = [];
                return $post;
            })->withQueryString();
        else {
            $followedUserIds = auth()->user()->following()->pluck('users.id');
            $posts = Post::whereIn('user_id', $followedUserIds)
                ->latest()
                ->paginate(3, ['*'], 'p')
                ->through(function ($post) {
                    $post->userLiked = $post->userLiked();
                    $post->numberOfComments = $post->comments()->count();
                    $post->numberOfLikes = $post->likes()->count();
                    $post->userBookmarked = $post->userBookmarked();
                    $post->comments = [];
                    return $post;
                })
                ->withQueryString();
        }


        $mostFollowedUsers = User::select(['id', 'name', 'username', 'avatar'])
            ->withCount('following')
            ->whereNotIn('users.id', auth()->user()->following()->select('users.id'))
            ->where('users.id', '!=', auth()->user()->id)
            ->orderBy('following_count', 'desc')
            ->take(5)
            ->get();


        $post = null;
        if ($request->has('pid')) {
            $post = Post::find($request->pid);
            if ($post) {
                $post['userLiked'] = $post->userLiked();
                $post['numberOfComments'] = $post->comments()->count();
                $post['numberOfLikes'] = $post->likes()->count();
                $post['likes'] = $post->likes()->count();
                $post['userBookmarked'] = $post->userBookmarked();
            }
        }

        return Inertia::render('Home', [
            'posts' => $posts,
            'followed' => $request->boolean('followed'),
            'mostFollowedUsers' => $mostFollowedUsers,
            'post' => $post,
            'comments' => $request->has('pid') ? Post::find($request->pid)->comments()->paginate(15, ['*'], 'c')->withQueryString() : null,
        ]);
    }

    public function store(Request $request)
    {
        $values = $request->validate([
            'description' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png',
            'video' => 'nullable|file|mimes:mp4,mov,avi',
            'enable_comments' => 'required|boolean',
            'enable_likes' => 'required|boolean',
            'image_description' => 'nullable|string|max:255'
        ]);

        $values['description'] = htmlspecialchars($values['description'], ENT_QUOTES, 'UTF-8');


        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = uniqid('video_') . '.' . $video->getClientOriginalExtension();
            $path = Storage::disk('public')->put('videos/' . $videoName, $video);
            $values['video'] = '/storage/' . $path;
            $values['image'] = null;
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

            $values['image'] = '/storage/images/medium_' . $filename;
        }

        $values['user_id'] = auth()->id();
        Post::create($values);
        return redirect('/');
    }
}
