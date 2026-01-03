<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class NewsletterVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = URL::temporarySignedRoute(
            'newsletter.verify',
            now()->addHours(48),
            [
                'token' => $notifiable->verification_token,
            ]
        );

        return (new MailMessage)
            ->subject('Confirm Your Newsletter Subscription - RTFM.guide')
            ->view('emails.newsletter-verification', [
                'url' => $verificationUrl,
                'unsubscribeUrl' => route('newsletter.unsubscribe', ['token' => $notifiable->unsubscribe_token]),
            ]);
    }
}
