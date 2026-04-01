<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ConversationResource;
use App\Models\Order;
use App\Models\Conversation;
use App\Services\ChatService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private ChatService $chatService,
    ) {}

    /**
     * Get messages from conversation and mark as read.
     */
    public function getMessages(Order $order, Request $request): JsonResponse
    {
        $conversation = $order->conversation;
        if (!$conversation) {
            return $this->errorResponse('No conversation found.', 404);
        }

        // Check authorization
        if ($request->user()->isCustomer() && $order->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        $messages = $this->chatService->getMessages($conversation, $request->user());

        return $this->successResponse(MessageResource::collection($messages));
    }

    /**
     * Send message in conversation.
     */
    public function sendMessage(SendMessageRequest $request, Order $order): JsonResponse
    {
        $conversation = $order->conversation;
        if (!$conversation) {
            return $this->errorResponse('No conversation found.', 404);
        }

        // Check authorization
        if ($request->user()->isCustomer() && $order->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        $message = $this->chatService->sendMessage(
            $conversation,
            $request->user(),
            $request->input('body')
        );

        return $this->successResponse(
            MessageResource::make($message),
            'Message sent.',
            201
        );
    }

    /**
     * Get all conversations with unread count (admin only).
     */
    public function getConversations(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        $conversations = $this->chatService->getAllConversations(
            $request->get('per_page', 15)
        );

        return $this->successResponse(ConversationResource::collection($conversations));
    }
}
