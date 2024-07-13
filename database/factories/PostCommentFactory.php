<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => function () {
                return Post::factory()->create()->id;
            },
            'user_id' => function () {
                return User::all()->random()->id;
            },
            'content' => fake()->sentence(15, true),
            'created_at' => fake()->dateTime(),
            'updated_at' => now(),
        ];
    }
}
