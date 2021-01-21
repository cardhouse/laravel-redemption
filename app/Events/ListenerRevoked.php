<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ListenerRevoked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $listener;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $listener)
    {
        $this->listener = $listener;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function handle()
    {
        HTTP::withHeaders([
            'Client-ID' => env('TWITCH_CLIENT_ID'),
            'Authorization' => 'Bearer ' . env('TWITCH_ACCESS_TOKEN'),
            'Content-Type' => 'application/json'
        ])->delete('https://api.twitch.tv/helix/eventsub/subscriptions', [
            'id' => $this->listener
        ]);
    }
}
