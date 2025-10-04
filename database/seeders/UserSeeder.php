<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Manually inserted users
        $manualUsers = [
            [
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('12345678'),
                'is_otp_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'email' => 'user@gmail.com',
                'role' => 'user',
                'password' => Hash::make('12345678'),
                'is_otp_verified' => true,
                'email_verified_at' => now(),
            ]
        ];

        // Insert manual users
        foreach ($manualUsers as $userData) {
            User::create($userData);
        }
    }
}

