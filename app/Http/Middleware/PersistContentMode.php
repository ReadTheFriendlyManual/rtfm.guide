<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PersistContentMode
{
    public function handle(Request $request, Closure $next)
    {
        $mode = $request->query('mode');

        if (is_string($mode) && in_array($mode, ['sfw', 'nsfw'], true)) {
            $request->session()->put('content_mode', $mode);
        }

        if (! $request->session()->has('content_mode')) {
            $request->session()->put('content_mode', 'sfw');
        }

        return $next($request);
    }
}
