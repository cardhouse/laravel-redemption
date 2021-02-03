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
        return app('twitch')->broadcaster(Auth::user()->twitch)->getRedemption();
    }
}
