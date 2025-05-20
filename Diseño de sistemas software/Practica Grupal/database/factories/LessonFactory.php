<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courseIds = Course::pluck('id')->toArray();

        return [
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'video_file_name' => fake()->imageURL(),
            'course_id' => fake()->randomElement($courseIds),
        ];
    }
}
