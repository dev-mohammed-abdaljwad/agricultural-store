<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ChatService
{
    /**
     * Get messages from conversation and mark as read for requester.
     */
    public function getMessages(Conversation $conversation, User $user): Collection
    {
        // Mark opposite sender's messages as read
        $senderType = $user->isAdmin() ? 'customer' : 'admin';
        
        Message::where('conversation_id', $conversation->id)
            ->where('sender_type', $senderType)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $conversation->messages()->get();
    }

    /**
     * Send a message in conversation.
     */
    public function sendMessage(Conversation $conversation, User $sender, string $body): Message
    {
        $senderType = $sender->isAdmin() ? 'admin' : 'customer';

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'sender_type' => $senderType,
            'body' => $body,
            'is_read' => false,
        ]);

        // Update conversation last message
        $conversation->update(['last_message_at' => now()]);

        return $message;
    }

    /**
     * Get all conversations with unread count (admin).
     */
    public function getAllConversations(int $perPage = 15)
    {
        return Conversation::with('customer', 'order', 'messages')
            ->latest('last_message_at')
            ->paginate($perPage)
            ->map(function ($conversation) {
                $conversation->unread_count = $conversation->unreadByAdmin()->count();
                return $conversation;
            });
    }
}
