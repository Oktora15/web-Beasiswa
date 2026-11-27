<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['draft', 'submitted', 'review', 'accepted', 'rejected']);

        return [
            'user_id' => User::factory(),
            'scholarship_id' => Scholarship::factory(),
            'status' => $status,
            'motivation' => $status === 'draft' ? null : fake()->paragraph(),
            'submitted_at' => $status === 'draft' ? null : now(),
        ];
    }
}
