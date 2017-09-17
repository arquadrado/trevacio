<?php

namespace App\Events;

use App\Models\ReadingSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReadingSessionAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $readingSession;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ReadingSession $readingSession)
    {
        $this->readingSession = $readingSession;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
