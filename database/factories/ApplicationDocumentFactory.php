<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\ApplicationDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApplicationDocument>
 */
class ApplicationDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'document_type' => fake()->randomElement(['CV', 'Transkrip nilai', 'Surat rekomendasi']),
            'original_name' => fake()->word().'.pdf',
            'path' => 'applications/'.fake()->numberBetween(1, 100).'/document.pdf',
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(1024, 500000),
        ];
    }
}
