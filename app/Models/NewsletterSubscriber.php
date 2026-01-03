<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    /** @use HasFactory<\Database\Factories\NewsletterSubscriberFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'email_verified_at',
        'verification_token',
        'unsubscribe_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Generate a unique unsubscribe token for the subscriber.
     */
    public static function generateUnsubscribeToken(): string
    {
        do {
            $token = Str::random(64);
        } while (self::where('unsubscribe_token', $token)->exists());

        return $token;
    }

    /**
     * Generate a unique verification token for the subscriber.
     */
    public static function generateVerificationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if the subscriber has verified their email.
     */
    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark the email as verified.
     */
    public function markEmailAsVerified(): bool
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'verification_token' => null,
        ]);

        return $this->save();
    }
}
