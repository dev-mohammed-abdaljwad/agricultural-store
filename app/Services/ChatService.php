<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Repositories\Chat\ChatRepository;
use App\Events\MessageSent;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ChatService
{
    public function __construct(
        protected ChatRepository $chatRepository
    ) {}

    // ──────────────────────────────────────────────
    // CONVERSATIONS
    // ──────────────────────────────────────────────

    /**
     * Get or create a conversation between two users
     */
    public function getOrCreateConversation(int $userId, int $targetUserId, ?int $orderId = null): Conversation
    {
        if ($userId === $targetUserId) {
            throw new \InvalidArgumentException('Cannot create conversation with yourself');
        }

        $conversation = $this->chatRepository->findConversationBetween($userId, $targetUserId);

        if (!$conversation) {
            $conversation = $this->chatRepository->createConversation($userId, $targetUserId, $orderId);
        }

        return $this->chatRepository->loadParticipants($conversation);
    }

    /**
     * Get all conversations for a user
     */
    public function getUserConversations(int $userId): Collection
    {
        return $this->chatRepository->getConversationsForUser($userId);
    }

    /**
     * Find a specific conversation
     */
    public function getConversation(int $conversationId): ?Conversation
    {
        return $this->chatRepository->findConversation($conversationId);
    }

    /**
     * Check if user is a participant in conversation
     */
    public function isParticipant(Conversation $conversation, int $userId): bool
    {
        return $conversation->hasParticipant($userId);
    }

    // ──────────────────────────────────────────────
    // MESSAGES
    // ──────────────────────────────────────────────

    /**
     * Get paginated messages for a conversation
     */
    public function getConversationMessages(int $conversationId, int $userId, int $perPage = 20): LengthAwarePaginator
    {
        $conversation = $this->getConversation($conversationId);

        if (!$conversation || !$this->isParticipant($conversation, $userId)) {
            throw new AuthorizationException('Unauthorized');
        }

        // Mark messages as read for this user
        $this->chatRepository->markMessagesAsRead($conversationId, $userId);

        return $this->chatRepository->getMessages($conversationId, $perPage);
    }

    /**
     * Send a message in a conversation
     */
    public function sendMessage(int $conversationId, int $userId, string $body): Message
    {
        $conversation = $this->getConversation($conversationId);

        if (!$conversation || !$this->isParticipant($conversation, $userId)) {
            throw new AuthorizationException('Unauthorized');
        }

        $message = $this->chatRepository->createMessage($conversationId, $userId, $body);

        // Update conversation last message time
        $conversation->update(['last_message_at' => now()]);

        // Broadcast event
        broadcast(new MessageSent($message));

        return $message;
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(int $conversationId, int $userId): int
    {
        $conversation = $this->getConversation($conversationId);

        if (!$conversation || !$this->isParticipant($conversation, $userId)) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->chatRepository->markMessagesAsRead($conversationId, $userId);
    }

    /**
     * Get unread message count for a user
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->chatRepository->getUnreadCountForUser($userId);
    }

    /**
     * Delete a message
     */
    public function deleteMessage(int $messageId, int $userId): void
    {
        $message = Message::find($messageId);

        if (!$message) {
            throw new \InvalidArgumentException('Message not found');
        }

        if ($message->user_id !== $userId) {
            throw new AuthorizationException('Unauthorized');
        }

        $this->chatRepository->deleteMessage($messageId);
    }

    // ──────────────────────────────────────────────
    // BACKWARD COMPATIBILITY (Order-based messaging)
    // ──────────────────────────────────────────────

    /**
     * Get messages from conversation and mark as read for requester. (For order messaging)
     */
    public function getMessages(Conversation $conversation, User $user): Collection
    {
        $unreadCount = $this->chatRepository->markMessagesAsRead($conversation->id, $user->id);

        return $conversation->messages()->get();
    }

    /**
     * Send a message in conversation. (For order messaging)
     */
    public function sendOrderMessage(Conversation $conversation, User $sender, string $body): Message
    {
        $message = $this->chatRepository->createMessage($conversation->id, $sender->id, $body);

        // Update conversation last message
        $conversation->update(['last_message_at' => now()]);

        // Broadcast event
        broadcast(new MessageSent($message));

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
