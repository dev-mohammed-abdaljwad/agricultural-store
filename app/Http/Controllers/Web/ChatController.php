<?php

namespace App\Http\Controllers\Web;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    // ──────────────────────────────────────────────
    // GENERAL CHAT (Customer to Admin)
    // ──────────────────────────────────────────────

    /**
     * Show customer's conversations list.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Get all conversations for this user
        $conversations = Conversation::with([
            'userA:id,name,email',
            'userB:id,name,email',
            'lastMessage' => fn($q) => $q->with('sender:id,name,email')->select('id', 'conversation_id', 'sender_id', 'body', 'created_at'),
        ])
        ->where(function ($query) use ($user) {
            $query->where('user_a_id', $user->id)
                  ->orWhere('user_b_id', $user->id);
        })
        ->latest('last_message_at')
        ->paginate(15);

        return view('chat.index', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * Start or get a conversation with a specific user.
     */
    public function startChat(User $user): View
    {
        $currentUser = auth()->user();

        // Prevent self-chat
        if ($user->id === $currentUser->id) {
            abort(403, 'Cannot chat with yourself');
        }

        // Find or create conversation
        $conversation = Conversation::where(function ($query) use ($currentUser, $user) {
            $query->where('user_a_id', $currentUser->id)
                  ->where('user_b_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('user_a_id', $user->id)
                  ->where('user_b_id', $currentUser->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_a_id' => $currentUser->id,
                'user_b_id' => $user->id,
                'last_message_at' => now(),
            ]);
        }

        // Load messages
        $messages = $conversation->messages()
            ->with('sender:id,name,email')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->update(['is_read' => true]);

        return view('chat.show', [
            'conversation' => $conversation,
            'otherUser' => $conversation->getOtherUser($currentUser->id),
            'messages' => $messages,
        ]);
    }

    /**
     * Show conversation with specific user.
     */
    public function show(Conversation $conversation): View
    {
        $currentUser = auth()->user();

        // Verify user is a participant
        if (!$conversation->hasParticipant($currentUser->id)) {
            abort(403, 'Unauthorized');
        }

        // Load data
        $conversation->load([
            'userA:id,name,email',
            'userB:id,name,email',
            'messages' => fn($q) => $q->with('sender:id,name,email')
                ->orderBy('created_at', 'asc'),
        ]);

        // Mark as read
        $conversation->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->update(['is_read' => true]);

        $otherUser = $conversation->getOtherUser($currentUser->id);

        return view('chat.show', [
            'conversation' => $conversation,
            'otherUser' => $otherUser,
            'messages' => $conversation->messages,
        ]);
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $userId = auth()->id();

        // Verify user is a participant
        if (!$conversation->hasParticipant($userId)) {
            abort(403, 'Unauthorized');
        }

        // Validate request
        $validated = $request->validate([
            'body' => 'required_without:attachment|string|max:5000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
        ]);

        // Handle file upload
        $attachmentUrl = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $isImage = in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif']);

            $path = $file->store("uploads/conversations/{$conversation->id}", 'public');
            $attachmentUrl = asset("storage/{$path}");
            $attachmentType = $isImage ? 'image' : 'file';
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'sender_type' => auth()->user()->isAdmin() ? 'admin' : 'customer',
            'body' => $validated['body'] ?? null,
            'attachment_url' => $attachmentUrl,
            'attachment_type' => $attachmentType,
            'is_read' => false,
        ]);

        // Load sender
        $message->load('sender:id,name,email');

        // Update conversation's last message timestamp
        $conversation->update(['last_message_at' => $message->created_at]);

        // Prepare broadcast data
        $messageData = [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'sender_type' => $message->sender_type,
            'body' => $message->body,
            'attachment_url' => $message->attachment_url,
            'attachment_type' => $message->attachment_type,
            'is_read' => $message->is_read,
            'created_at' => $message->created_at->toIso8601String(),
        ];

        $senderData = [
            'id' => $message->sender->id,
            'name' => $message->sender->name,
            'email' => $message->sender->email,
        ];

        // Broadcast message
        broadcast(new MessageSent($conversation->id, $messageData, $senderData))->toOthers();

        return response()->json([
            'success' => true,
            'message' => [
                ...$messageData,
                'sender' => $senderData,
            ],
        ]);
    }

    // ──────────────────────────────────────────────
    // ORDER-SPECIFIC CHAT (Kept for compatibility)
    // ──────────────────────────────────────────────

    /**
     * Show customer chat for a specific order.
     */
    public function showOrderChat(Order $order): View
    {
        // Verify customer owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Load order with items count
        $order->loadCount('items');

        // Get or create conversation for this order
        $conversation = Conversation::firstOrCreate(
            ['order_id' => $order->id],
            [
                'customer_id' => auth()->id(),
                'last_message_at' => now(),
            ]
        );

        // Load messages for this conversation
        $messages = $conversation->messages()
            ->with('sender:id,name,email')
            ->get();

        // Mark customer messages as read
        $conversation->markAsReadByAdmin();

        return view('chat.order', [
            'order' => $order,
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }
}
