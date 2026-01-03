<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailVerificationToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public static function generate(User $user): self
    {
        // Generate a random token for the email
        $rawToken = Str::random(64);

        return DB::transaction(function () use ($user, $rawToken) {
            // Delete any existing tokens for this user
            self::where('user_id', $user->id)->delete();

            // Store the hashed token in the database
            $verificationToken = self::create([
                'user_id' => $user->id,
                'token' => hash('sha256', $rawToken),
                'expires_at' => now()->addHours(24),
            ]);

            // Set the raw token on the model instance so it can be used in the email
            $verificationToken->rawToken = $rawToken;

            return $verificationToken;
        });
    }

    /**
     * Find a token by its raw (unhashed) value.
     */
    public static function findByRawToken(string $rawToken): ?self
    {
        $hashedToken = hash('sha256', $rawToken);

        return self::where('token', $hashedToken)->with('user')->first();
    }
}
