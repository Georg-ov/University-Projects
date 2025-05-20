<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $roleType = fake()->randomElement(['STUDENT', 'TEACHER', 'ADMIN']);

        $subscriptionType = ($roleType === 'STUDENT')
            ? fake()->randomElement(['FREEMIUM'])
            : 'PREMIUM';

        $subscriptionExpirationDate = ($subscriptionType === 'PREMIUM')
            ? fake()->dateTimeBetween('0 months', '1 months')->format('Y-m-d')
            : '9999-12-31';

        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'age' => fake()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'role_type' => $roleType,
            'subscription_type' => $subscriptionType,
            'image_profile' => 'app_images/default_profile_picture',
            'subscription_expiration_date' => $subscriptionExpirationDate,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
