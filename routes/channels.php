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
 * Only the customer and admin can access the conversation channel.
 */
Broadcast::channel('conversation.{conversationId}', function (User $user, string $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // Allow customer to access their conversation
    if ($conversation->customer_id === $user->id) {
        return true;
    }

    // Allow admin to access any conversation
    if ($user->isAdmin()) {
        return true;
    }

    return false;
});
