<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::all()->random()->id;
            },
            'description' => fake()->text(),
            'location' => fake()->city(),
            'image' => function () {
                $image = "https://picsum.photos/1920/1080";
                $filename = uniqid() . '.webp';

                $optimizedImageBig = Image::make($image)->fit(1400, 1080)->encode('webp', 60);
                $optimizedImageMedium = Image::make($image)->fit(550, 468)->encode('webp', 80);
                $optimizedImageMini = Image::make($image)->fit(309, 309)->encode('webp', 80);

                // Save the optimized images
                Storage::disk('public')->put('images/big_' . $filename, $optimizedImageBig);
                Storage::disk('public')->put('images/medium_' . $filename, $optimizedImageMedium);
                Storage::disk('public')->put('images/small_' . $filename, $optimizedImageMini);

                return '/storage/images/medium_' . $filename;
            },
            'video' => null,
            'enable_comments' => fake()->randomElement([true]),
            'enable_likes' => fake()->randomElement([true, false]),
            'image_description' => null,
            // 'url' => $faker->url,  // For videos you might need a different approach
            'created_at' => fake()->dateTime(),
            'updated_at' => now(),
        ];
    }
}
