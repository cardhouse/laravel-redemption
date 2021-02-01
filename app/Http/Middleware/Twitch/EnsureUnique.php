<?php

namespace App\Http\Middleware\Twitch;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EnsureUnique
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Cache::has($request->json('event.id'))) {
            abort(204);
        }
        return $next($request);
    }
}
