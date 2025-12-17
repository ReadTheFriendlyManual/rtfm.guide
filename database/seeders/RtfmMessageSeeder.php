<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RtfmMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();

        $messages = [
            'You should have RTFM... but we did it for you.',
            'RTFM: Read The Friendly Manual',
            "Stop googling, start RTFM'ing",
            "RTFM: Because the manual isn't just for decoration",
            'You had one job: RTFM',
            'RTFM before you break something',
            "Manual? We don't need no stinking manual!",
            "RTFM: Read The Fine Manual (we read it so you don't have to)",
            'Documentation is your friend. RTFM!',
            'RTFM: The original Stack Overflow',
        ];

        foreach ($messages as $message) {
            \App\Models\RtfmMessage::create([
                'user_id' => $user->id,
                'message' => $message,
                'is_approved' => true,
                'usage_count' => rand(1, 100),
            ]);
        }
    }
}
