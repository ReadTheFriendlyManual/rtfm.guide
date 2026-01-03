<?php

use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Notifications\NewsletterVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    Notification::fake();
});

describe('Newsletter Subscription', function () {
    it('allows guest users to subscribe to newsletter', function () {
        $response = post(route('newsletter.subscribe'), [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Thanks for subscribing! Please check your email to confirm your subscription.');

        assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'subscriber@example.com')->first();

        expect($subscriber->verification_token)->not->toBeNull();
        expect($subscriber->unsubscribe_token)->not->toBeNull();
        expect($subscriber->email_verified_at)->toBeNull();

        Notification::assertSentTo($subscriber, NewsletterVerificationNotification::class);
    });

    it('updates existing user newsletter subscription when they subscribe via footer', function () {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'newsletter_subscribed' => false,
        ]);

        post(route('newsletter.subscribe'), [
            'email' => 'user@example.com',
        ]);

        $user->refresh();

        expect($user->newsletter_subscribed)->toBeTrue();

        // Should not create a newsletter subscriber record
        expect(NewsletterSubscriber::where('email', 'user@example.com')->exists())->toBeFalse();
    });

    it('resends verification email if unverified subscriber resubmits', function () {
        $subscriber = NewsletterSubscriber::factory()->unverified()->create([
            'email' => 'resend@example.com',
        ]);

        post(route('newsletter.subscribe'), [
            'email' => 'resend@example.com',
        ]);

        Notification::assertSentTo($subscriber, NewsletterVerificationNotification::class);
    });

    it('prevents duplicate subscriptions for verified subscribers', function () {
        NewsletterSubscriber::factory()->create([
            'email' => 'verified@example.com',
        ]);

        $response = post(route('newsletter.subscribe'), [
            'email' => 'verified@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'This email is already subscribed to our newsletter.']);
    });

    it('prevents duplicate subscriptions for subscribed users', function () {
        User::factory()->create([
            'email' => 'subscribed@example.com',
            'newsletter_subscribed' => true,
        ]);

        $response = post(route('newsletter.subscribe'), [
            'email' => 'subscribed@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'This email is already subscribed to our newsletter.']);
    });

    it('requires a valid email address', function () {
        $response = post(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'Please enter a valid email address.']);
    });

    it('requires email to be provided', function () {
        $response = post(route('newsletter.subscribe'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'Please enter your email address.']);
    });
});

describe('Newsletter Verification', function () {
    it('verifies newsletter subscription with valid token', function () {
        $subscriber = NewsletterSubscriber::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'newsletter.verify',
            now()->addHours(48),
            ['token' => $subscriber->verification_token]
        );

        $response = get($url);

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Thank you! Your newsletter subscription has been confirmed.');

        $subscriber->refresh();

        expect($subscriber->hasVerifiedEmail())->toBeTrue();
        expect($subscriber->verification_token)->toBeNull();
    });

    it('rejects verification with invalid signature', function () {
        $subscriber = NewsletterSubscriber::factory()->unverified()->create();

        $url = route('newsletter.verify', ['token' => $subscriber->verification_token]);

        $response = get($url);

        $response->assertForbidden();
    });

    it('rejects verification with expired signature', function () {
        $subscriber = NewsletterSubscriber::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'newsletter.verify',
            now()->subHour(),
            ['token' => $subscriber->verification_token]
        );

        $response = get($url);

        $response->assertForbidden();
    });

    it('handles verification for already verified subscribers gracefully', function () {
        $subscriber = NewsletterSubscriber::factory()->unverified()->create();
        $token = $subscriber->verification_token;

        // Verify the subscriber first
        $subscriber->markEmailAsVerified();

        // Try to verify again with the same token
        $url = URL::temporarySignedRoute(
            'newsletter.verify',
            now()->addHours(48),
            ['token' => $token]
        );

        $response = get($url);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Invalid verification token.');
    });

    it('rejects verification with invalid token', function () {
        $url = URL::temporarySignedRoute(
            'newsletter.verify',
            now()->addHours(48),
            ['token' => 'invalid-token']
        );

        $response = get($url);

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Invalid verification token.');
    });
});

describe('Newsletter Unsubscribe', function () {
    it('allows guest subscribers to unsubscribe', function () {
        $subscriber = NewsletterSubscriber::factory()->create();

        $response = get(route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]));

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'You have been unsubscribed from our newsletter.');

        expect(NewsletterSubscriber::where('id', $subscriber->id)->exists())->toBeFalse();
    });

    it('allows registered users to unsubscribe', function () {
        $user = User::factory()->create([
            'newsletter_subscribed' => true,
        ]);

        $token = hash_hmac('sha256', $user->id, config('app.key'));

        $response = get(route('newsletter.unsubscribe', ['token' => $token]));

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'You have been unsubscribed from our newsletter.');

        $user->refresh();

        expect($user->newsletter_subscribed)->toBeFalse();
    });

    it('rejects unsubscribe with invalid token', function () {
        $response = get(route('newsletter.unsubscribe', ['token' => 'invalid-token']));

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Invalid unsubscribe link.');
    });
});

describe('Newsletter Registration Integration', function () {
    it('subscribes user to newsletter when checkbox is checked during registration', function () {
        // Ensure registration is enabled
        \App\Models\Setting::set('registration_enabled', true);

        $response = post('/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'newsletter_subscribed' => '1',
        ]);

        $user = User::where('email', 'newuser@example.com')->first();

        expect($user)->not->toBeNull();
        expect($user->newsletter_subscribed)->toBeTrue();
    });

    it('does not subscribe user to newsletter when checkbox is unchecked', function () {
        // Ensure registration is enabled
        \App\Models\Setting::set('registration_enabled', true);

        $response = post('/register', [
            'name' => 'New User',
            'email' => 'newuser2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'newuser2@example.com')->first();

        expect($user)->not->toBeNull();
        expect($user->newsletter_subscribed)->toBeFalse();
    });

    it('defaults newsletter_subscribed to false when field is completely absent', function () {
        // Ensure registration is enabled
        \App\Models\Setting::set('registration_enabled', true);

        // Simulate registration request without newsletter_subscribed field at all
        $response = post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // Intentionally omitting newsletter_subscribed field
        ]);

        $response->assertRedirect();

        $user = User::where('email', 'test@example.com')->first();

        expect($user)->not->toBeNull();
        expect($user->newsletter_subscribed)->toBeFalse();
        expect($user->newsletter_subscribed)->toBe(false); // Explicit false check
    });
});

describe('Edge Cases', function () {
    it('handles case-insensitive email matching for subscriptions', function () {
        NewsletterSubscriber::factory()->create([
            'email' => 'Test@Example.com',
        ]);

        $response = post(route('newsletter.subscribe'), [
            'email' => 'test@example.com',
        ]);

        // Note: This depends on database collation. Most databases are case-insensitive by default
        // If this test fails, it may need adjustment based on your database configuration
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'This email is already subscribed to our newsletter.']);

        // Verify no duplicate subscriber was created
        expect(NewsletterSubscriber::count())->toBe(1);
    });

    it('trims whitespace from email addresses', function () {
        $response = post(route('newsletter.subscribe'), [
            'email' => '  subscriber@example.com  ',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify email was stored without whitespace
        assertDatabaseHas('newsletter_subscribers', [
            'email' => 'subscriber@example.com',
        ]);

        // Ensure the email with spaces doesn't exist
        expect(NewsletterSubscriber::where('email', 'like', '%  %')->exists())->toBeFalse();
    });

    it('generates unique unsubscribe tokens for each subscriber', function () {
        $subscriber1 = NewsletterSubscriber::factory()->create();
        $subscriber2 = NewsletterSubscriber::factory()->create();

        expect($subscriber1->unsubscribe_token)->not->toBe($subscriber2->unsubscribe_token);
    });

    it('generates unique verification tokens for each subscriber', function () {
        $subscriber1 = NewsletterSubscriber::factory()->unverified()->create();
        $subscriber2 = NewsletterSubscriber::factory()->unverified()->create();

        expect($subscriber1->verification_token)->not->toBe($subscriber2->verification_token);
    });
});
