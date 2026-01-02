<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResendVerificationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:resend-verification-email {user_id : The ID of the user or all for all unverified users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend the email verification email to a specific user or all unverified users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $userId = $this->argument('user_id');

        if ($userId === 'all') {
            $users = User::whereNull('email_verified_at')->get();
            foreach ($users as $user) {
                $user->sendEmailVerificationNotification();
                $this->info("Resent verification email to user ID: $user->id");
            }
        } else {
            $user = User::find($userId);
            if ($user && is_null($user->email_verified_at)) {
                $user->sendEmailVerificationNotification();
                $this->info("Resent verification email to user ID: $user->id");
            } else {
                $this->error('User not found or already verified.');
            }
        }

        return 0;
    }
}
