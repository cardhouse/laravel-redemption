<?php

namespace App\Events;

use App\Models\Twitch\Redemption;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RedemptionReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $redemption;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Redemption $redemption)
    {
        $this->redemption = $redemption;
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
