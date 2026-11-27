<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'gpa' => fake()->randomFloat(2, 2.5, 4),
            'birth_date' => fake()->dateTimeBetween('-27 years', '-18 years')->format('Y-m-d'),
            'country_of_origin' => fake()->randomElement(['Indonesia', 'Malaysia', 'Singapura']),
            'ielts_score' => fake()->randomFloat(1, 5, 9),
            'toefl_score' => fake()->numberBetween(60, 120),
        ];
    }
}
