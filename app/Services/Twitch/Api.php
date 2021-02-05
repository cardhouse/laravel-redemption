<?php

namespace App\Services\Twitch;

use App\Models\Twitch\Account;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Api
{
    protected $token;
    protected $broadcaster;
    protected $status;
    protected $method = 'get';
    protected $base = 'https://api.twitch.tv/helix';
    protected $uri = '';

    public function __construct()
    {
        $this->token = env('TWITCH_ACCESS_TOKEN');
        $this->broadcaster = null;
        $this->status = null;
    }

    public function broadcaster(Account $broadcaster)
    {
        $this->token = $broadcaster->token;
        $this->broadcaster = $broadcaster;

        return $this;
    }

    public function status(string $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get collection of custom rewards offered for a broadcaster
     * Optionally include Reward ID to get one result.
     *
     * @param string $reward_id
     * @return \Illuminate\Support\Collection
     */
    public function getReward($reward_id = null)
    {
        $this->method = 'get';
        $this->uri = '/channel_points/custom_rewards';
        $payload = [
            'broadcaster_id' =>  $this->broadcaster->id,
            'id' => $reward_id
        ];
        
        $response = $this->_call($payload);

        return collect($response->json('data'));
    }

    public function getRedemption($id = null)
    {
        $this->method = 'get';
        $this->uri = '/channel_points/custom_rewards/redemptions';
        $payload = [
            'broadcaster_id' =>  $this->broadcaster->id,
            'reward_id' => $id,
            'status' => $this->status
        ];

        $payload = array_filter($payload);
        $response = $this->_call($payload);

        return collect($response->json('data'));
    }

    protected function headers()
    {
        return [
            'Client-ID' => env('TWITCH_CLIENT_ID'),
            'Content-Type' => 'application/json'
        ];
    }

    protected function _call($payload = null)
    {
        $method = $this->method;
        
        $response = Http::withToken($this->token)
            ->withHeaders($this->headers())
            ->$method($this->getEndpoint(), $payload);

        if ($response->status() === 401) {
            $this->broadcaster($this->broadcaster->refresh());

            $response = Http::withToken($this->token)
            ->withHeaders($this->headers())
            ->$method($this->getEndpoint(), $payload);
        }

        return $response;
    }

    protected function getEndpoint()
    {
        return $this->base . $this->uri;
    }
}
