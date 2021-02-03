<?php

namespace App\Events;

use App\Models\Twitch\Redemption;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CountUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $redemption;
    public $count;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Redemption $redemption, $count)
    {
        $this->redemption = $redemption;
        $this->count = $count ?: 0;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->redemption->getChannel());
    }
}
