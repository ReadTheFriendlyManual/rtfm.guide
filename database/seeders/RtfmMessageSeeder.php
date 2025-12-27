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
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

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
            ['message' => 'RTFM: Read The Fucking Manual, you absolute dickhead!', 'is_nsfw' => true],
            ['message' => 'RTFM before you fuck up everything, you useless piece of shit!', 'is_nsfw' => true],
            ['message' => 'Stop being a dumbass and RTFM, fuckface!', 'is_nsfw' => true],
            ['message' => 'RTFM: Read The Fucking Manual (because you\'re too fucking stupid to figure it out on your own)', 'is_nsfw' => true],
            ['message' => 'Holy fucking shit, just RTFM already you lazy bastard!', 'is_nsfw' => true],
            ['message' => 'RTFM you incompetent fuck! How the hell did you get this far?', 'is_nsfw' => true],
            ['message' => 'Read The Fucking Manual before you break everything, dipshit!', 'is_nsfw' => true],
            ['message' => 'RTFM: Because apparently you can\'t read for shit', 'is_nsfw' => true],
            ['message' => 'Seriously, RTFM you goddamn moron!', 'is_nsfw' => true],
            ['message' => 'RTFM before I lose my fucking mind watching you stumble around like an idiot!', 'is_nsfw' => true],
            ['message' => 'Just fucking RTFM already! It\'s not that fucking hard!', 'is_nsfw' => true],
            ['message' => 'RTFM you insufferable dickwad!', 'is_nsfw' => true],
            ['message' => 'Read The Fucking Manual, numbnuts! We did the work for you!', 'is_nsfw' => true],
            ['message' => 'RTFM: The manual is right fucking there. Use your goddamn eyes!', 'is_nsfw' => true],
            ['message' => 'Stop wasting everyone\'s time and RTFM, shithead!', 'is_nsfw' => true],
            ['message' => 'RTFM or fuck off! Those are your options!', 'is_nsfw' => true],
            ['message' => 'Congratulations! You just wasted 2 hours when you could have RTFM\'d in 5 minutes, genius!', 'is_nsfw' => true],
            ['message' => 'RTFM before asking stupid fucking questions, jackass!', 'is_nsfw' => true],
            ['message' => 'Read The Fucking Manual! How many times do we have to tell you this, you dense motherfucker?', 'is_nsfw' => true],
            ['message' => 'RTFM you absolute waste of oxygen!', 'is_nsfw' => true],
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
