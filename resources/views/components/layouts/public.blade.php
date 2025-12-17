@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')

        @if($title)
            <title>{{ $title }} - RTFM.guide</title>
        @else
            <title>RTFM.guide - Read The F***ing Manual (but we did it for you)</title>
        @endif
    </head>
    <body class="min-h-screen antialiased">
        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        @fluxScripts
    </body>
</html>
