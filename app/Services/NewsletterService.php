<?php

namespace App\Services;

use App\Http\Controllers\NewsletterController;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NewsletterService
{
    /**
     * Get all verified newsletter subscribers (both guests and users).
     */
    public function getAllSubscribers(): Collection
    {
        // Get guest subscribers
        $guestSubscribers = NewsletterSubscriber::query()
            ->whereNotNull('email_verified_at')
            ->get()
            ->map(function ($subscriber) {
                return [
                    'email' => $subscriber->email,
                    'unsubscribe_url' => route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]),
                    'type' => 'guest',
                    'notifiable' => $subscriber,
                ];
            });

        // Get subscribed users
        $userSubscribers = User::query()
            ->where('newsletter_subscribed', true)
            ->whereNotNull('email_verified_at')
            ->get()
            ->map(function ($user) {
                return [
                    'email' => $user->email,
                    'unsubscribe_url' => NewsletterController::getUserUnsubscribeUrl($user),
                    'type' => 'user',
                    'notifiable' => $user,
                ];
            });

        return $guestSubscribers->concat($userSubscribers);
    }

    /**
     * Get the total count of verified subscribers.
     */
    public function getSubscriberCount(): int
    {
        $guestCount = NewsletterSubscriber::whereNotNull('email_verified_at')->count();
        $userCount = User::where('newsletter_subscribed', true)
            ->whereNotNull('email_verified_at')
            ->count();

        return $guestCount + $userCount;
    }

    /**
     * Send a newsletter to all subscribers.
     *
     * @param  class-string  $notificationClass  The notification class to send
     */
    public function sendNewsletter(string $notificationClass, array $data = []): int
    {
        $subscribers = $this->getAllSubscribers();
        $sentCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                $notifiable = $subscriber['notifiable'];
                $notification = new $notificationClass(...array_values($data));

                $notifiable->notify($notification);
                $sentCount++;
            } catch (\Exception $e) {
                // Log error but continue with other subscribers
                Log::error('Failed to send newsletter to '.$subscriber['email'].': '.$e->getMessage());
            }
        }

        return $sentCount;
    }

    /**
     * Check if an email is subscribed to the newsletter.
     */
    public function isSubscribed(string $email): bool
    {
        // Check guest subscribers
        $guestSubscriber = NewsletterSubscriber::where('email', $email)
            ->whereNotNull('email_verified_at')
            ->exists();

        if ($guestSubscriber) {
            return true;
        }

        // Check user subscribers
        return User::where('email', $email)
            ->where('newsletter_subscribed', true)
            ->exists();
    }
}
