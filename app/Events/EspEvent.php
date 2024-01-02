<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EspEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $status_sistem, $status_hujan, $intensitas, $status_roof;
    /**
     * Create a new event instance.
     */
    public function __construct($status_sistem, $status_hujan, $intensitas, $status_roof)
    {
        $this->status_sistem = $status_sistem;
        $this->status_hujan = $status_hujan;
        $this->intensitas = $intensitas;
        $this->status_roof = $status_roof;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('esp-channel'),
        ];
    }

    public function broadcastAs()
    {
        return 'esp-event';
    }
}
