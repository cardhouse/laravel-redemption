<?php

namespace App\Services\Twitch;

use App\Models\Twitch\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Api
{
    protected $token;
    protected $broadcaster;

    public function __construct()
    {
        $this->token = env('TWITCH_ACCESS_TOKEN');
        $this->broadcaster = null;
    }

    public function broadcaster(Account $broadcaster)
    {
        $this->token = $broadcaster->token;
        $this->broadcaster = $broadcaster;

        return $this;
    }

    public function getRedemption($reward_id = null)
    {
        $response = $this->_call(
            'get', 
            'https://api.twitch.tv/helix/channel_points/custom_rewards',
            [
                'broadcaster_id' =>  $this->broadcaster->id,
                'id' => $reward_id
            ]
        );

        if ($response->status() === 401) {
            $refreshed = $this->broadcaster->refresh();
            $this->broadcaster($refreshed);

            return $this->_call(
                'get', 
                'https://api.twitch.tv/helix/channel_points/custom_rewards',
                [
                    'broadcaster_id' =>  $this->broadcaster->id,
                    'id' => $reward_id
                ]
            );
        }

        return collect($response->json('data'));
    }

    protected function headers()
    {
        return [
            'Client-ID' => env('TWITCH_CLIENT_ID'),
            'Content-Type' => 'application/json'
        ];
    }

    protected function _call($method, $endpoint, $payload)
    {
        return Http::withToken($this->token)
            ->withHeaders($this->headers())
            ->$method($endpoint, $payload);
    }
}
