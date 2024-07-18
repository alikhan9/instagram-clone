<?php

namespace Database\Factories;

use App\Models\PostLike;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostLike>
 */
class PostLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        ];
    }

    /**
     * Indicate that the post like should be for a specific post.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forPost(int $postId): Factory
    {
        return $this->state(function (array $attributes) use ($postId) {
            $userId = User::whereNotIn('id', PostLike::where('post_id', $postId)->pluck('user_id'))->first()->id;

            return [
                'post_id' => $postId,
                'user_id' => $userId,
            ];
        });
    }
}
