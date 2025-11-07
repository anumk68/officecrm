<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BroadcastNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected array $channels;
    protected string $eventName;
    public array $data;
    /**
     * @param array|string $channels The channel(s) to broadcast on.
     * @param string $eventName The name of the event for the frontend listener.
     * @param array $data The data payload.
     */
    public function __construct(array|string $channels, string $eventName, array $data)
    {
        $this->channels = is_array($channels) ? $channels : [$channels];
        $this->eventName = $eventName;
        $this->data = $data;
        Log::info("Notification Event Constructed for '{$this->eventName}'");
    }

    public function broadcastOn(): array
    {
        return array_map(function ($channelName) {
            // Automatically create PrivateChannel objects for user-specific channels
            return str_starts_with($channelName, 'App.Models.User.')
                ? new PrivateChannel($channelName)
                : new Channel($channelName);
        }, $this->channels);
    }
    public function broadcastAs(): string
    {
        return $this->eventName;
    }
}
