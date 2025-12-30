<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test user for easy login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
            'reputation_points' => 10000,
            'trust_level' => 'admin',
        ]);

        // Create 50 random users with varied attributes
        User::factory()->count(50)->create();
    }
}
