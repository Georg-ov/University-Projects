<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreditCard>
 */
class CreditCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => fake()->unique()->numberBetween(1, User::count()),
            'card_number' => fake()->creditCardNumber,
            'cardholder_name' => fake()->name,
            'expiration_month' => fake()->numberBetween(1, 12),
            'expiration_year' => fake()->numberBetween(date('Y'), date('Y') + 10),
            'cvv' => fake()->numberBetween(100, 999)
        ];
    }
}
