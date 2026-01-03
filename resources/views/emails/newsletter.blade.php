@extends('emails.layout')

@section('content')
{{-- Newsletter Header with Issue Number --}}
@if(isset($issueNumber))
<div style="text-align: center; margin-bottom: 24px;">
    <span style="display: inline-block; background: linear-gradient(135deg, #be185d 0%, #9f1239 100%); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;">
        Issue #{{ $issueNumber }}
    </span>
</div>
@endif

<h1 class="email-title">{{ $title ?? 'RTFM.guide Newsletter' }}</h1>

@if(isset($subtitle))
<p class="email-text" style="font-size: 18px; color: #404040; font-weight: 500;">
    {{ $subtitle }}
</p>
@endif

{{-- Main Content --}}
<div style="margin: 32px 0;">
    {!! $content !!}
</div>

{{-- Featured Guides Section (if provided) --}}
@if(isset($featuredGuides) && count($featuredGuides) > 0)
<div class="email-divider"></div>

<h2 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 20px; font-weight: 700; color: #262626; margin: 24px 0 16px 0;">
    📚 Featured Guides This Week
</h2>

@foreach($featuredGuides as $guide)
<div style="background: linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%); border-left: 4px solid #be185d; padding: 20px; margin-bottom: 16px; border-radius: 8px;">
    <h3 style="font-family: 'Bricolage Grotesque', sans-serif; font-size: 16px; font-weight: 600; color: #262626; margin: 0 0 8px 0;">
        <a href="{{ $guide['url'] }}" style="color: #262626; text-decoration: none;">
            {{ $guide['title'] }}
        </a>
    </h3>
    @if(isset($guide['description']))
    <p style="font-size: 14px; color: #525251; margin: 0 0 12px 0; line-height: 1.6;">
        {{ $guide['description'] }}
    </p>
    @endif
    <a href="{{ $guide['url'] }}" style="display: inline-block; color: #be185d; font-size: 14px; font-weight: 600; text-decoration: none;">
        Read Guide →
    </a>
</div>
@endforeach
@endif

{{-- Call to Action (if provided) --}}
@if(isset($ctaText) && isset($ctaUrl))
<div style="text-align: center; margin: 40px 0;">
    <a href="{{ $ctaUrl }}" class="email-button">
        {{ $ctaText }}
    </a>
</div>
@endif

{{-- Footer Message --}}
<div class="email-divider"></div>

<p class="email-text" style="font-size: 14px; color: #737372; text-align: center;">
    Thanks for being part of the RTFM.guide community! 🙌
</p>
@endsection

@section('subcopy')
You're receiving this email because you subscribed to the RTFM.guide newsletter.
<br><br>
<a href="{{ $unsubscribeUrl ?? '#' }}" style="color: #be185d;">Unsubscribe from this newsletter</a>
&nbsp;&nbsp;•&nbsp;&nbsp;
<a href="{{ config('app.url') }}/account/preferences" style="color: #be185d;">Update preferences</a>
@endsection
