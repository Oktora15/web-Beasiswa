<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $students = [
            1 => 'lpdp-reguler-magister',
            2 => 'erasmus-mundus-joint-masters',
            3 => 'australia-awards-indonesia',
        ];

        foreach ($students as $number => $slug) {
            $email = config("seeding.students.{$number}.email") ?: [
                1 => 'alya.maharani@beasiswahub.test',
                2 => 'bima.pratama@beasiswahub.test',
                3 => 'citra.rahman@beasiswahub.test',
            ][$number];
            $student = User::where('email', $email)->first();
            $scholarship = Scholarship::where('slug', $slug)->first();

            if ($student && $scholarship) {
                $student->bookmarks()->firstOrCreate(['scholarship_id' => $scholarship->id]);
            }
        }
    }
}
