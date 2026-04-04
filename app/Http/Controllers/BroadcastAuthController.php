<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\Broadcasters\Pusher;
use Illuminate\Http\Request;

class BroadcastAuthController extends Controller
{
    /**
     * Handle Pusher private channel authentication requests.
     * 
     * This endpoint is called by Pusher when a client attempts to subscribe
     * to a private channel. It validates that the authenticated user has
     * permission to access the requested channel.
     * 
     * For conversation channels (private-conversation_{id}), we verify:
     * - The user is a participant in the conversation, OR
     * - The user is an admin
     */
    public function authenticate(Request $request)
    {
        // Get the channel and socket_id from the request
        $channelName = $request->input('channel_name');
        $socketId = $request->input('socket_id');

        // Ensure user is authenticated
        if (!auth()->check()) {
            return response('Unauthorized', 403);
        }

        $user = auth()->user();

        // Parse the channel name to extract the type and ID
        try {
            if (str_starts_with($channelName, 'private-conversation_')) {
                // Extract conversation ID from channel name: private-conversation_5
                $conversationId = (int) substr($channelName, strlen('private-conversation_'));
                
                // Get the conversation
                $conversation = Conversation::find($conversationId);
                
                if (!$conversation) {
                    \Log::warning("[BroadcastAuth] Conversation not found: {$conversationId}");
                    return response('Unauthorized', 403);
                }

                // Check if user is authorized to access this conversation
                $isParticipant = $conversation->user_a_id === $user->id || $conversation->user_b_id === $user->id;
                $isAdmin = $user->isAdmin();

                if (!$isParticipant && !$isAdmin) {
                    \Log::warning("[BroadcastAuth] User {$user->id} unauthorized for conversation {$conversationId}");
                    return response('Unauthorized', 403);
                }

                \Log::info("[BroadcastAuth] User {$user->id} authenticated for conversation {$conversationId}");
            } elseif (str_starts_with($channelName, 'private-notifications_')) {
                // Extract user ID from channel name: private-notifications_5
                $notificationUserId = (int) substr($channelName, strlen('private-notifications_'));
                
                // Only the user can receive their own notifications
                if ($notificationUserId !== $user->id) {
                    \Log::warning("[BroadcastAuth] User {$user->id} unauthorized for notifications of user {$notificationUserId}");
                    return response('Unauthorized', 403);
                }

                \Log::info("[BroadcastAuth] User {$user->id} authenticated for their notifications channel");
            } else {
                \Log::warning("[BroadcastAuth] Unknown channel type: {$channelName}");
                return response('Unauthorized', 403);
            }
        } catch (\Exception $e) {
            \Log::error("[BroadcastAuth] Error authenticating channel: " . $e->getMessage());
            return response('Unauthorized', 403);
        }

        // Generate and return the authentication response
        // This uses Pusher's authentication mechanism
        try {
            $pusher = new Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                config('broadcasting.connections.pusher.options')
            );

            // Generate the authentication token
            $auth = $pusher->socket_auth($channelName, $socketId);

            return response()->json(['auth' => $auth]);
        } catch (\Exception $e) {
            \Log::error("[BroadcastAuth] Failed to generate auth token: " . $e->getMessage());
            return response('Unable to generate auth token', 500);
        }
    }
}
