<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'firebase_uid' => 'admin-uid-' . time(),
            'name' => 'Admin User',
            'email' => 'admin@yourstory.local',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Moderator
        User::create([
            'firebase_uid' => 'moderator-uid-' . time(),
            'name' => 'Moderator User',
            'email' => 'moderator@yourstory.local',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_MODERATOR,
        ]);

        // 5 Regular Members
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'firebase_uid' => 'member-uid-' . $i . '-' . time(),
                'name' => "Member User $i",
                'email' => "member$i@yourstory.local",
                'password' => bcrypt('password123'),
                'role' => User::ROLE_MEMBER,
            ]);
        }
    }
}
