<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        // Create 15 teams with random owners
        Team::factory()->count(15)->create([
            'owner_user_id' => fn () => $users->random()->id,
        ]);
    }
}
