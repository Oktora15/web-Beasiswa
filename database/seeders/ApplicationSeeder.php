<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $studentEmail = config('seeding.students.1.email') ?: 'alya.maharani@beasiswahub.test';
        $student = User::where('email', $studentEmail)->first();
        $scholarship = Scholarship::where('slug', 'lpdp-reguler-magister')->first();

        if ($student && $scholarship) {
            Application::firstOrCreate(
                ['user_id' => $student->id, 'scholarship_id' => $scholarship->id],
                ['status' => 'submitted', 'motivation' => Str::repeat('Saya ingin mengembangkan keahlian dan berkontribusi bagi Indonesia melalui program ini. ', 2), 'submitted_at' => now()]
            );
        }
    }
}
