<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @inertiaHead
    </head>
    <body class="bg-slate-50 text-slate-900 antialiased dark:bg-slate-900 dark:text-white">
        @inertia
    </body>
</html>
