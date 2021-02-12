<?php

namespace App\Http\Controllers;

use App\Services\TwitchSubscriptionService;
use Illuminate\Support\Facades\Auth;

class Redemption extends Controller
{
    public function setup()
    {
        $listener = TwitchSubscriptionService::subscribe(Auth::user()->twitch->id);
    
        return view('twitch.redemptions.info', [
            'broadcaster' =>  Auth::user()->twitch,
            'listener' => $listener
        ]);
    }

    public function index()
    {
        $rewards = app('twitch')->broadcaster(Auth::user()->twitch)->getReward();
        TwitchSubscriptionService::subscribe(Auth::user()->twitch->id);
        
        $valid = $rewards->filter(function ($reward) {
            return (
              $reward['max_per_stream_setting']['is_enabled']
              && !$reward['should_redemptions_skip_request_queue']
            );
        })->pluck('id', 'title');

        return view('twitch.counters')
            ->with('rewards', $valid)
            ->with('broadcaster', Auth::user()->twitch);
    }
}
