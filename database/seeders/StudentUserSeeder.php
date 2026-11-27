<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        $isLocal = app()->environment(['local', 'testing']);
        $students = [
            ['name' => 'Alya Putri Maharani', 'email' => 'alya.maharani@beasiswahub.test', 'password' => 'StudentDemo1!2026'],
            ['name' => 'Bima Aditya Pratama', 'email' => 'bima.pratama@beasiswahub.test', 'password' => 'StudentDemo2!2026'],
            ['name' => 'Citra Nabila Rahman', 'email' => 'citra.rahman@beasiswahub.test', 'password' => 'StudentDemo3!2026'],
        ];

        foreach ($students as $index => $student) {
            $number = $index + 1;
            $email = config("seeding.students.{$number}.email") ?: ($isLocal ? $student['email'] : null);
            $password = config("seeding.students.{$number}.password") ?: ($isLocal ? $student['password'] : null);

            if (! $email || ! $password) {
                throw new RuntimeException("Atur SEED_STUDENT_{$number}_EMAIL dan SEED_STUDENT_{$number}_PASSWORD sebelum membuat akun mahasiswa.");
            }

            User::updateOrCreate(
                ['email' => $email],
                ['name' => $student['name'], 'password' => $password, 'role' => 'student']
            );
        }
    }
}
