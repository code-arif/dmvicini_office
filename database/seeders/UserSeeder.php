<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Users
        $adminUsers = [
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'access_level' => 'full',
                'is_active' => true,
                'role' => 'admin',
            ],
            [
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'access_level' => 'full',
                'is_active' => true,
                'role' => 'admin',
            ],
        ];

        // Regular Users with different access levels
        $regularUsers = [
            [
                'email' => 'john.doe@investment.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'access_level' => 'full',
                'is_active' => true,
                'role' => 'user',
                'provisional_expires_at' => null,
            ],
            [
                'email' => 'jane.smith@advisory.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'access_level' => 'provisional',
                'is_active' => true,
                'role' => 'user',
                'provisional_expires_at' => now()->addDays(7),
            ],
            [
                'email' => 'mike.johnson@retail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'access_level' => 'limited',
                'is_active' => false,
                'role' => 'user',
                'provisional_expires_at' => null,
            ],
        ];

        // Insert admin users
        foreach ($adminUsers as $userData) {
            User::create($userData);
        }

        // Insert regular users
        foreach ($regularUsers as $userData) {
            User::create($userData);
        }

        // Output summary
        $this->command->info('✓ Created 2 admin users');
        $this->command->info('✓ Created 3 regular users with different access levels');
        $this->command->info('✓ Total users: 5');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->line('Admin 1: admin@example.com / password123');
        $this->command->line('Admin 2: superadmin@example.com / password123');
        $this->command->line('User 1 (Full): john.doe@investment.com / password123');
        $this->command->line('User 2 (Provisional): jane.smith@advisory.com / password123');
        $this->command->line('User 3 (Limited): mike.johnson@retail.com / password123');
    }
}
