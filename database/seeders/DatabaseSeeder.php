<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ini_set('memory_limit', '-1');  // Increase memory limit

        // Create 10 users
        User::inRandomOrder()->limit(20)->each(function ($user) {
            // Each user will have up to 7 posts
            $posts = Post::factory(random_int(0,5))->make();
            $user->posts()->saveMany($posts);

            // Each post will have between 1 to 10 comments
            $posts->each(function ($post) {
                $comments = PostComment::factory(rand(1, 100))->create(['post_id' => $post->id]);
                $post->comments()->saveMany($comments);
                PostLike::factory(rand(1,100))->forPost($post->id)->create();
            });
        });
    }
}
