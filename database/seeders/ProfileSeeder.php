<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = [
            ['email' => config('seeding.students.1.email') ?: 'alya.maharani@beasiswahub.test', 'gpa' => 3.74, 'birth_date' => '2002-04-15', 'ielts_score' => 7.0],
            ['email' => config('seeding.students.2.email') ?: 'bima.pratama@beasiswahub.test', 'gpa' => 3.52, 'birth_date' => '2001-09-22', 'toefl_score' => 104],
            ['email' => config('seeding.students.3.email') ?: 'citra.rahman@beasiswahub.test', 'gpa' => 3.88, 'birth_date' => '2003-01-08', 'ielts_score' => 7.5, 'toefl_score' => 108],
        ];

        foreach ($profiles as $profile) {
            $user = User::where('email', $profile['email'])->first();

            if ($user) {
                $attributes = $profile;
                unset($attributes['email']);
                $user->profile()->firstOrCreate([], $attributes);
            }
        }
    }
}
