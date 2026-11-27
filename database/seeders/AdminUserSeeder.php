<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $isLocal = app()->environment(['local', 'testing']);
        $email = config('seeding.admin.email') ?: ($isLocal ? 'admin@beasiswahub.test' : null);
        $password = config('seeding.admin.password') ?: ($isLocal ? 'AdminDemo!2026' : null);

        if (! $email || ! $password) {
            throw new RuntimeException('Atur SEED_ADMIN_EMAIL dan SEED_ADMIN_PASSWORD sebelum membuat akun admin.');
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Administrator Beasiswa', 'password' => $password, 'role' => 'admin']
        );
    }
}
