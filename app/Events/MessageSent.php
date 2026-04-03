<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    /**
     * Message data to broadcast
     */
    public int $conversationId;
    public array $messageData;
    public array $senderData;

    /**
     * Create a new event instance.
     */
    public function __construct(int $conversationId, array $messageData, array $senderData)
    {
        $this->conversationId = $conversationId;
        $this->messageData = $messageData;
        $this->senderData = $senderData;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->messageData,
            'sender' => $this->senderData,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Determine if the event should be broadcast now.
     * For real-time chat, we want immediate broadcasting.
     */
    public function shouldBroadcastNow(): bool
    {
        return true;
    }
}
