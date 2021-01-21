<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TwitchSubscriptionService
{
    const API_ENDPOINT = 'https://api.twitch.tv/helix/eventsub/subscriptions';

    public static function subscribe($channel)
    {
        $response = Http::withHeaders(self::getHeaders())->post(self::API_ENDPOINT, [
            // 'type' => $channel,
            'type' => 'channel.channel_points_custom_reward_redemption.add',
            'version' => 1,
            'condition' => [
                'broadcaster_user_id' => '548965051'
            ],
            'transport' => [
                'method' => 'webhook',
                'callback' => 'https://39bd86910dd6.ngrok.io:443/api/twitch/callback',
                'secret' => 'open_sesame'
            ]
        ]);

        return $response;
    }

    private static function getHeaders()
    {
        return [
            'Client-ID' => env('TWITCH_CLIENT_ID'),
            'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
            'Content-Type' => 'application/json'
        ];
    }
}