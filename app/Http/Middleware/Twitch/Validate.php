<?php

namespace App\Http\Middleware\Twitch;

use Closure;
use Illuminate\Http\Request;

class Validate
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
        $signature = $request->header('Twitch-Eventsub-Message-Signature');
        $timestamp = $request->header('Twitch-Eventsub-Message-Timestamp');
        $id = $request->header('Twitch-Eventsub-Message-Id');
        $message = $id . $timestamp . $request->getContent();
        $secret = env('TWITCH_MESSAGE_SECRET', "314159");
        $algo = 'sha256';
        $check = hash_hmac($algo, $message, $secret);
        
        if($signature != $algo.'='.$check) {
            abort(401);
        }
        return $next($request);
    }
}
