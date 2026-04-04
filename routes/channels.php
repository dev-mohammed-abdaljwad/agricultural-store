<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The required channels may be returned from events
| while the optional channel parameters may be specified as wildcards.
|
*/

/**
 * Authorize user access to private conversation channel.
 * Both participants in the conversation can access this channel.
 * When a message is sent, both users receive it via WebSocket.
 */
Broadcast::channel('conversation.{conversationId}', function (User $user, string $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // Allow either participant to access their conversation
    if ($conversation->user_a_id === $user->id || $conversation->user_b_id === $user->id) {
        return true;
    }

    // Allow admin to access any conversation
    if ($user->isAdmin()) {
        return true;
    }

    return false;
});

/**
 * Authorize user access to their private notifications channel.
 * Only the user can receive notifications on their own channel.
 * This is used for unread count updates and initial notifications.
 */
Broadcast::channel('notifications.{userId}', function (User $user, string $userId) {
    return (int) $user->id === (int) $userId;
});

/**
 * Authorize admin access to admin notifications channel.
 * Only admin users can receive admin notifications.
 */
Broadcast::channel('admin.notifications', function (User $user) {
    return $user->isAdmin();
});

/**
 * Authorize customer access to their notifications channel.
 * Only the customer can receive notifications for their orders.
 */
Broadcast::channel('customer.notifications.{customerId}', function (User $user, string $customerId) {
    return (int) $user->id === (int) $customerId;
});

/**
 * Authorize user access to specific order channel.
 * Customer or admin can access the order channel.
 */
Broadcast::channel('order.{orderId}', function (User $user, string $orderId) {
    $order = \App\Models\Order::find($orderId);
    
    if (!$order) {
        return false;
    }
    
    // Customer can access their own order
    if ($order->customer_id === $user->id) {
        return true;
    }
    
    // Admin can access any order
    if ($user->isAdmin()) {
        return true;
    }
    
    return false;
});
