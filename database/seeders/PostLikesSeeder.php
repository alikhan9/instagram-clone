<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch 10 random posts
        Post::all()->random(10)->each(function (Post $post) {
            // Get user IDs who have already liked the current post
            $likedUserIds = PostLike::where('post_id', $post->id)->pluck('user_id');

            // Get users who have not yet liked this post
            User::whereNotIn('id', $likedUserIds)->limit(rand(1, 40))->get()->each(function (User $user) use ($post) {
                // Check if the user exists before creating the like
                if (!PostLike::where('post_id', $post->id)->where('user_id', $user->id)->exists()) {
                    $likes = PostLike::factory(1)->create([
                        'post_id' => $post->id,
                        'user_id' => $user->id,
                    ]);
                    $post->likes()->saveMany($likes);
                }
            });
        });
    }
}
