@extends('emails.layout')

@section('content')
<h1 class="email-title">Welcome to RTFM.guide! 🎉</h1>

<p class="email-text">
    Hi <strong>{{ $userName }}</strong>,
</p>

<p class="email-text">
    Thanks for verifying your email! You're now part of a growing community of developers who believe in reading (and writing) the friendly manual.
</p>

<div class="email-divider"></div>

<p class="email-text" style="font-size: 14px;">
    <strong>Here's what you can do now:</strong>
</p>

<ul style="color: #525251; font-size: 15px; line-height: 1.8; margin: 0 0 24px 0; padding-left: 24px;">
    <li><strong>Create Guides</strong> - Share your knowledge with the community</li>
    <li><strong>Comment & Engage</strong> - Join discussions and help others</li>
    <li><strong>Save Favorites</strong> - Build your personal learning library</li>
    <li><strong>Customize Your Profile</strong> - Add your bio and social links</li>
</ul>

<div style="text-align: center; margin: 32px 0;">
    <a href="{{ $dashboardUrl }}" class="email-button">
        Go to Dashboard
    </a>
</div>

<div class="email-divider"></div>

<p class="email-text" style="font-size: 14px;">
    <strong>Need help getting started?</strong>
</p>

<p class="email-text" style="font-size: 14px; color: #737372;">
    Browse our <a href="{{ $guidesUrl }}" style="color: #be185d; text-decoration: none; font-weight: 500;">community guides</a> to see what others have created, or jump right in and <a href="{{ $createGuideUrl }}" style="color: #be185d; text-decoration: none; font-weight: 500;">create your first guide</a>.
</p>

<p class="email-text" style="margin-top: 32px;">
    Happy documenting!<br>
    <strong>The RTFM.guide Team</strong>
</p>
@endsection

@section('subcopy')
If you have any questions or feedback, feel free to reach out. We're here to help!
@endsection
