<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            StudentUserSeeder::class,
            ScholarshipSeeder::class,
            CriterionSeeder::class,
            CriterionScholarshipSeeder::class,
            ProfileSeeder::class,
            ApplicationSeeder::class,
            BookmarkSeeder::class,
            ApplicationDocumentSeeder::class,
        ]);
    }
}
