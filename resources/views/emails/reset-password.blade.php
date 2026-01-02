@extends('emails.layout')

@section('content')
<h1 class="email-title">Reset Your Password</h1>

<p class="email-text">
    You are receiving this email because we received a password reset request for your account.
</p>

<div style="text-align: center; margin: 32px 0;">
    <a href="{{ $url }}" class="email-button">
        Reset Password
    </a>
</div>

<p class="email-text">
    This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.
</p>

<div class="email-divider"></div>

<p class="email-text" style="font-size: 14px;">
    <strong>Security Tip:</strong> If you did not request a password reset, no further action is required. Your password will remain unchanged.
</p>

<p class="email-text" style="font-size: 14px; color: #737372;">
    For security reasons, we recommend choosing a strong password that:
</p>

<ul style="color: #737372; font-size: 14px; line-height: 1.7; margin: 0 0 24px 0; padding-left: 24px;">
    <li>Is at least 8 characters long</li>
    <li>Contains a mix of letters, numbers, and symbols</li>
    <li>Is unique to RTFM.guide</li>
</ul>
@endsection

@section('subcopy')
If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <a href="{{ $url }}" style="color: #be185d; word-break: break-all;">{{ $url }}</a>
@endsection
