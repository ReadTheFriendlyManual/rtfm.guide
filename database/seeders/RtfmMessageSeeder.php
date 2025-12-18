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
            // SFW messages
            ['message' => 'You should have RTFM... but we did it for you.', 'is_nsfw' => false],
            ['message' => 'RTFM: Read The Friendly Manual', 'is_nsfw' => false],
            ['message' => "Stop googling, start RTFM'ing", 'is_nsfw' => false],
            ['message' => "RTFM: Because the manual isn't just for decoration", 'is_nsfw' => false],
            ['message' => 'You had one job: RTFM', 'is_nsfw' => false],
            ['message' => 'RTFM before you break something', 'is_nsfw' => false],
            ['message' => "Manual? We don't need no stinking manual!", 'is_nsfw' => false],
            ['message' => "RTFM: Read The Fine Manual (we read it so you don't have to)", 'is_nsfw' => false],
            ['message' => 'Documentation is your friend. RTFM!', 'is_nsfw' => false],
            ['message' => 'RTFM: The original Stack Overflow', 'is_nsfw' => false],

            // NSFW messages
            ['message' => 'RTFM: Read The Fucking Manual, asshole!', 'is_nsfw' => true],
            ['message' => 'RTFM before you fuck up everything!', 'is_nsfw' => true],
            ['message' => 'Stop being a dumbass and RTFM!', 'is_nsfw' => true],
            ['message' => 'RTFM: Read The Fucking Manual (because you\'re too stupid to figure it out)', 'is_nsfw' => true],
            ['message' => 'Holy shit, just RTFM already!', 'is_nsfw' => true],
            ['message' => 'RTFM you incompetent fuck!', 'is_nsfw' => true],
            ['message' => 'Read The Fucking Manual before you break everything, dipshit!', 'is_nsfw' => true],
            ['message' => 'RTFM: Because apparently you skipped that day in school', 'is_nsfw' => true],
        ];

        foreach ($messages as $messageData) {
            \App\Models\RtfmMessage::create([
                'user_id' => $user->id,
                'message' => $messageData['message'],
                'is_approved' => true,
                'is_nsfw' => $messageData['is_nsfw'],
                'usage_count' => rand(1, 100),
            ]);
        }
    }
}
