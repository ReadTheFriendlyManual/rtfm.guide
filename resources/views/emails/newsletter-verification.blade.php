@extends('emails.layout')

@section('content')
<h1 class="email-title">Confirm Your Newsletter Subscription</h1>

<p class="email-text">
    Thanks for subscribing to the RTFM.guide newsletter! We're thrilled to have you join our community.
</p>

<p class="email-text">
    To complete your subscription and start receiving updates about new guides, features, and tips, please confirm your email address by clicking the button below:
</p>

<div style="text-align: center; margin: 32px 0;">
    <a href="{{ $url }}" class="email-button">
        Confirm Subscription
    </a>
</div>

<p class="email-text">
    This verification link will expire in 48 hours.
</p>

<div class="email-divider"></div>

<p class="email-text" style="font-size: 14px;">
    <strong>What you'll receive:</strong>
</p>

<ul style="color: #525251; font-size: 14px; line-height: 1.7; margin: 0 0 24px 0; padding-left: 24px;">
    <li>Weekly highlights of the best new guides</li>
    <li>Updates on new features and improvements</li>
    <li>Curated tips and tricks from the community</li>
    <li>Exclusive early access to new content</li>
</ul>

<p class="email-text" style="font-size: 13px; color: #737372;">
    You can unsubscribe at any time by clicking the unsubscribe link at the bottom of any newsletter email.
</p>
@endsection

@section('subcopy')
If you're having trouble clicking the "Confirm Subscription" button, copy and paste the URL below into your web browser: <a href="{{ $url }}" style="color: #be185d; word-break: break-all;">{{ $url }}</a>
<br><br>
If you did not subscribe to this newsletter, you can safely ignore this email or <a href="{{ $unsubscribeUrl }}" style="color: #be185d;">unsubscribe here</a>.
@endsection
