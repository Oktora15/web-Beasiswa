<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\Scholarship;
use Illuminate\Database\Seeder;

class CriterionScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $criterionIds = Criterion::query()->orderBy('id')->pluck('id')->all();

        if ($criterionIds === []) {
            return;
        }

        Scholarship::query()->each(function (Scholarship $scholarship) use ($criterionIds): void {
            $firstCriterion = $criterionIds[($scholarship->id - 1) % count($criterionIds)];
            $secondCriterion = $criterionIds[$scholarship->id % count($criterionIds)];

            $scholarship->criteria()->sync(array_values(array_unique([$firstCriterion, $secondCriterion])));
        });
    }
}
