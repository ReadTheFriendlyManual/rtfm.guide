@extends('emails.layout')

@section('content')
<h1 class="email-title">New Reply to Your Comment</h1>

<p class="email-text">
    <strong>{{ $replyUserName }}</strong> replied to your comment on the guide <strong>{{ $guideTitle }}</strong>.
</p>

<div style="background-color: #f5f5f4; border-left: 4px solid #be185d; padding: 16px 20px; margin: 24px 0; border-radius: 8px;">
    <p style="margin: 0; color: #525251; font-size: 15px; line-height: 1.6;">
        {{ Str::limit($replyContent, 200) }}
    </p>
</div>

<div style="text-align: center; margin: 32px 0;">
    <a href="{{ $url }}" class="email-button">
        View Reply
    </a>
</div>

<div class="email-divider"></div>

<p class="email-text" style="font-size: 14px; color: #737372;">
    You're receiving this because you commented on a guide. You can manage your notification preferences in your account settings.
</p>
@endsection

@section('subcopy')
If you're having trouble clicking the "View Reply" button, copy and paste the URL below into your web browser: <a href="{{ $url }}" style="color: #be185d; word-break: break-all;">{{ $url }}</a>
@endsection
