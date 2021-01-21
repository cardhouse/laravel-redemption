<?php

namespace App\Services\Twitch;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Api
{
    public static function getRedemption($broadcaster_id, $reward_id = null)
    {
        $response = Http::withToken(Auth::user()->twitch->token)->withHeaders([
            'Client-ID' => env('TWITCH_CLIENT_ID'),
            'Content-Type' => 'application/json'
        ])->get('https://api.twitch.tv/helix/channel_points/custom_rewards', [
            'broadcaster_id' =>  $broadcaster_id,
            'id' => $reward_id
        ]);
    
        return $response;
    }
}
