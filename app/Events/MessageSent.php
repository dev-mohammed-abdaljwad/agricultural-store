<?php

namespace App\Events;

use App\Models\Conversation;
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
    public ?int $receiverId = null;

    /**
     * Create a new event instance.
     */
    public function __construct(int $conversationId, array $messageData, array $senderData, ?int $receiverId = null)
    {
        $this->conversationId = $conversationId;
        $this->messageData = $messageData;
        $this->senderData = $senderData;
        $this->receiverId = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     * 
     * Broadcasts to:
     * 1. Private conversation channel (for real-time updates in full chat page)
     * 2. Receiver's notification channel (for popup auto-open)
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('conversation_' . $this->conversationId),
        ];

        // Also broadcast to receiver's notification channel for popup auto-open
        if ($this->receiverId) {
            $channels[] = new PrivateChannel('notifications_' . $this->receiverId);
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->messageData,
            'sender' => $this->senderData,
            'conversation_id' => $this->conversationId,
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
