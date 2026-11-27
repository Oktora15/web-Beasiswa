<?php

namespace Database\Seeders;

use App\Models\Criterion;
use Illuminate\Database\Seeder;

class CriterionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = collect(['Prestasi akademik', 'Kegiatan sosial', 'Kemampuan bahasa', 'Rencana studi'])
            ->map(fn (string $name) => Criterion::firstOrCreate(['name' => $name]));

    }
}
