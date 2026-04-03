<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'user_a_id',
        'user_b_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // RELATIONSHIPS
    // ──────────────────────────────────────────────

    /**
     * Get the order associated with the conversation (if any).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer (user) associated with the conversation.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get user A (first participant).
     */
    public function userA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    /**
     * Get user B (second participant).
     */
    public function userB(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    /**
     * Get all messages in the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the last message in the conversation.
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest('created_at');
    }

    /**
     * Get unread messages for admin.
     */
    public function unreadForAdmin()
    {
        return $this->messages()
                    ->where('sender_type', 'customer')
                    ->where('is_read', false);
    }

    // ──────────────────────────────────────────────
    // HELPER METHODS
    // ──────────────────────────────────────────────

    /**
     * Get count of unread messages for admin.
     */
    public function getUnreadCountAttribute(): int
    {
        return $this->unreadForAdmin()->count();
    }

    /**
     * Check if conversation has unread messages.
     */
    public function hasUnread(): bool
    {
        return $this->unreadForAdmin()->exists();
    }

    /**
     * Mark all customer messages as read by admin.
     */
    public function markAsReadByAdmin(): void
    {
        $this->unreadForAdmin()->update(['is_read' => true]);
    }

    /**
     * Get the other user in the conversation.
     */
    public function getOtherUser(int $userId): ?User
    {
        if ($this->user_a_id === $userId) {
            return $this->userB;
        } elseif ($this->user_b_id === $userId) {
            return $this->userA;
        }
        return null;
    }

    /**
     * Check if user is a participant in this conversation.
     */
    public function hasParticipant(int $userId): bool
    {
        return $this->user_a_id === $userId || $this->user_b_id === $userId;
    }

    /**
     * Check if this is a general conversation (not order-specific).
     */
    public function isGeneralConversation(): bool
    {
        return $this->order_id === null;
    }

    /**
     * Check if this is an order-specific conversation.
     */
    public function isOrderConversation(): bool
    {
        return $this->order_id !== null;
    }
}
