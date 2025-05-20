<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::where('role_type', 'TEACHER')->pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        return [
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image_file_name' => fake()->imageURL(),
            'visibility' => fake()->boolean(),
            'publish_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'is_free' => fake()->boolean(),
            'user_id' => fake()->randomElement($userIds),
            'category_id' => fake()->randomElement($categoryIds),
        ];
    }
}
