@extends('emails.layout')

@section('content')
<h1 class="email-title">Verify Your Email Address</h1>

<p class="email-text">
    Thanks for signing up for RTFM.guide! We're excited to have you join our community of developers.
</p>

<p class="email-text">
    To get started and access all features, please verify your email address by clicking the button below:
</p>

<div style="text-align: center; margin: 32px 0;">
    <a href="{{ $url }}" class="email-button">
        Verify Email Address
    </a>
</div>

<p class="email-text">
    This verification link will expire in {{ config('auth.verification.expire', 60) }} minutes.
</p>

<div class="email-divider"></div>

<p class="email-text" style="font-size: 14px;">
    <strong>What's next?</strong>
</p>

<p class="email-text" style="font-size: 14px;">
    Once verified, you'll be able to:
</p>

<ul style="color: #525251; font-size: 14px; line-height: 1.7; margin: 0 0 24px 0; padding-left: 24px;">
    <li>Create and publish guides</li>
    <li>Comment on guides and engage with the community</li>
    <li>Save your favorite guides for later</li>
    <li>Customize your profile and preferences</li>
</ul>
@endsection

@section('subcopy')
If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: <a href="{{ $url }}" style="color: #be185d; word-break: break-all;">{{ $url }}</a>
<br><br>
If you did not create an account, no further action is required.
@endsection
