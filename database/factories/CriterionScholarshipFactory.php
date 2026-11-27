<?php

namespace Database\Factories;

use App\Models\Criterion;
use App\Models\CriterionScholarship;
use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CriterionScholarship>
 */
class CriterionScholarshipFactory extends Factory
{
    protected $model = CriterionScholarship::class;

    public function definition(): array
    {
        return [
            'criterion_id' => Criterion::factory(),
            'scholarship_id' => Scholarship::factory(),
        ];
    }
}
