<?php

declare(strict_types=1);

namespace App\Repositories\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ChatRepository
{
    
    // ──────────────────────────────────────────────
    // CONVERSATIONS
    // ──────────────────────────────────────────────

    /**
     * Find conversation between two specific users
     */
    public function findConversationBetween(int $userAId, int $userBId): ?Conversation
    {
        $minUserId = min($userAId, $userBId);
        $maxUserId = max($userAId, $userBId);

        return Conversation::where('user_a_id', $minUserId)
            ->where('user_b_id', $maxUserId)
            ->first();
    }

    /**
     * Find a specific conversation by ID
     */
    public function findConversation(int $conversationId): ?Conversation
    {
        return Conversation::find($conversationId);
    }

    /**
     * Create a new conversation
     */
    public function createConversation(int $userAId, int $userBId, ?int $orderId = null): Conversation
    {
        $minUserId = min($userAId, $userBId);
        $maxUserId = max($userAId, $userBId);

        return Conversation::create([
            'user_a_id' => $minUserId,
            'user_b_id' => $maxUserId,
            'order_id' => $orderId,
            'last_message_at' => null,
        ]);
    }

    /**
     * Load participants with select columns
     */
    public function loadParticipants(Conversation $conversation): Conversation
    {
        return $conversation->load([
            'userA:id,name,email',
            'userB:id,name,email',
        ]);
    }

    /**
     * Get all conversations for a user ordered by latest message
     */
    public function getConversationsForUser(int $userId): Collection
    {
        return Conversation::where('user_a_id', $userId)
            ->orWhere('user_b_id', $userId)
            ->with([
                'userA:id,name,email',
                'userB:id,name,email',
            ])
            ->latest('last_message_at')
            ->get()
            ->map(function (Conversation $conversation) use ($userId) {
                $otherUser = $conversation->getOtherUser($userId);

                return [
                    'id' => $conversation->id,
                    'other_user' => $otherUser?->only(['id', 'name', 'email']),
                    'last_message' => $conversation->getLastMessage()?->only(['id', 'body', 'created_at']),
                    'unread_count' => $conversation->getUnreadCountFor($userId),
                    'last_message_at' => $conversation->last_message_at,
                ];
            });
    }

    // ──────────────────────────────────────────────
    // MESSAGES
    // ──────────────────────────────────────────────

    /**
     * Create a new message
     */
    public function createMessage(int $conversationId, int $senderId, string $body): Message
    {
        return Message::create([
            'conversation_id' => $conversationId,
            'user_id' => $senderId,
            'body' => $body,
            'is_read' => false,
        ]);
    }

    /**
     * Get paginated messages for a conversation
     */
    public function getMessages(int $conversationId, int $perPage = 20): LengthAwarePaginator
    {
        return Message::where('conversation_id', $conversationId)
            ->with('sender:id,name,email')
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Mark messages as read for a user in a conversation
     */
    public function markMessagesAsRead(int $conversationId, int $userId): int
    {
        return Message::where('conversation_id', $conversationId)
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get total unread count for a user
     */
    public function getUnreadCountForUser(int $userId): int
    {
        return Message::whereHas('conversation', function ($query) use ($userId) {
            $query->where('user_a_id', $userId)->orWhere('user_b_id', $userId);
        })
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Delete a message (soft delete)
     */
    public function deleteMessage(int $messageId): void
    {
        $message = Message::find($messageId);
        if ($message) {
            $message->delete();
        }
    }
}
