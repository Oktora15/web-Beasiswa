<?php

namespace Database\Factories;

use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Scholarship>
 */
class ScholarshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $returnObligation = fake()->boolean(30);

        return [
            'title' => $title = fake()->unique()->words(3, true).' Scholarship',
            'slug' => Str::slug($title),
            'provider' => fake()->company(),
            'country' => fake()->randomElement(['Jepang', 'Australia', 'Britania Raya', 'Jerman', 'Amerika Serikat', 'Korea Selatan', 'Prancis', 'Belanda']),
            'degree_level' => fake()->randomElement(['S1', 'S2', 'S3']),
            'funding_type' => fake()->randomElement(['fully', 'partial']),
            'language_test' => fake()->randomElement(['none', 'ielts', 'toefl', 'ielts_or_toefl', 'waiver']),
            'return_obligation' => $returnObligation,
            'return_duration_years' => $returnObligation ? fake()->numberBetween(1, 5) : null,
            'min_gpa' => fake()->optional()->randomFloat(2, 2.5, 3.8),
            'max_age' => fake()->optional()->numberBetween(25, 35),
            'open_date' => today()->subDays(10),
            'deadline' => today()->addDays(fake()->numberBetween(15, 120)),
            'official_url' => fake()->url(),
            'description' => fake()->paragraphs(3, true),
            'is_active' => true,
        ];
    }
}
