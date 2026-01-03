<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Notifications\NewsletterVerificationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class NewsletterController extends Controller
{
    /**
     * Subscribe to the newsletter.
     */
    public function subscribe(NewsletterSubscribeRequest $request): RedirectResponse
    {
        $email = $request->validated()['email'];

        // Check if this is a registered user (case-insensitive)
        $user = User::whereRaw('LOWER(email) = ?', [strtolower(trim($email))])->first();

        if ($user) {
            // Update the user's newsletter subscription status
            $user->update(['newsletter_subscribed' => true]);

            return back()->with('success', 'You have been subscribed to our newsletter!');
        }

        // Check if an unverified subscriber already exists (case-insensitive)
        $subscriber = NewsletterSubscriber::whereRaw('LOWER(email) = ?', [strtolower(trim($email))])
            ->whereNull('email_verified_at')
            ->first();

        if ($subscriber) {
            // Resend verification email
            $subscriber->notify(new NewsletterVerificationNotification);

            return back()->with('success', 'A verification email has been sent to your inbox. Please check your email.');
        }

        // Create new subscriber
        $subscriber = NewsletterSubscriber::create([
            'email' => $email,
            'verification_token' => NewsletterSubscriber::generateVerificationToken(),
            'unsubscribe_token' => NewsletterSubscriber::generateUnsubscribeToken(),
        ]);

        // Send verification email
        $subscriber->notify(new NewsletterVerificationNotification);

        return back()->with('success', 'Thanks for subscribing! Please check your email to confirm your subscription.');
    }

    /**
     * Verify a newsletter subscription.
     */
    public function verify(Request $request): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }

        $subscriber = NewsletterSubscriber::where('verification_token', $request->token)->first();

        if (! $subscriber) {
            return redirect('/')->with('error', 'Invalid verification token.');
        }

        if ($subscriber->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'Your email is already verified.');
        }

        $subscriber->markEmailAsVerified();

        return redirect('/')->with('success', 'Thank you! Your newsletter subscription has been confirmed.');
    }

    /**
     * Unsubscribe from the newsletter.
     */
    public function unsubscribe(Request $request): RedirectResponse
    {
        $token = $request->token;

        // Try to find a newsletter subscriber
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if ($subscriber) {
            $subscriber->delete();

            return redirect('/')->with('success', 'You have been unsubscribed from our newsletter.');
        }

        // Try to find a user by generating unsubscribe token from user ID
        // This allows users to unsubscribe via a link in their emails
        // Use cursor() for memory-efficient iteration through potentially large datasets
        $user = null;
        foreach (User::where('newsletter_subscribed', true)->cursor() as $potentialUser) {
            if ($this->generateUserUnsubscribeToken($potentialUser->id) === $token) {
                $user = $potentialUser;
                break; // Early exit when match is found
            }
        }

        if ($user) {
            $user->update(['newsletter_subscribed' => false]);

            return redirect('/')->with('success', 'You have been unsubscribed from our newsletter.');
        }

        return redirect('/')->with('error', 'Invalid unsubscribe link.');
    }

    /**
     * Generate an unsubscribe token for a user.
     */
    protected function generateUserUnsubscribeToken(int $userId): string
    {
        return hash_hmac('sha256', $userId, config('app.key'));
    }

    /**
     * Get the unsubscribe URL for a user.
     */
    public static function getUserUnsubscribeUrl(User $user): string
    {
        $token = hash_hmac('sha256', $user->id, config('app.key'));

        return route('newsletter.unsubscribe', ['token' => $token]);
    }
}
