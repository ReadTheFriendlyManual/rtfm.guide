<?php

namespace App\Console\Commands;

use App\Models\EmailVerificationToken;
use Illuminate\Console\Command;

class CleanupExpiredEmailVerificationTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-verification:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired email verification tokens';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = EmailVerificationToken::where('expires_at', '<', now())->delete();

        $this->info("Deleted {$count} expired email verification token(s).");

        return self::SUCCESS;
    }
}
