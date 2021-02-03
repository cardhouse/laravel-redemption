<?php

namespace App\Http\Controllers;

use App\Services\TwitchSubscriptionService;
use Illuminate\Support\Facades\Auth;

class Redemption extends Controller
{
    
    protected $broadcaster;

    public function __construct()
    {
        $this->broadcaster = Auth::user()->twitch;
    }
    public function setup()
    {
        $listener = TwitchSubscriptionService::subscribe($this->broadcaster->id);
    
        return view('twitch.redemptions.info', [
            'broadcaster' => $this->broadcaster,
            'listener' => $listener
        ]);
    }

    public function index()
    {
        return app('twitch')->broadcaster($this->broadcaster)->getRedemption();
    }
}
