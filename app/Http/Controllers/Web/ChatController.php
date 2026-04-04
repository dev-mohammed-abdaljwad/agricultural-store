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
     * Returns JSON for AJAX requests, view for regular requests.
     */
    public function index()
    {
        $user = auth()->user();

        try {
            // Check if this is an AJAX request
            $isJson = request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest';
            
            if ($isJson) {
                // Return JSON for floating widget and popups
                $conversations = Conversation::with([
                    'userA:id,name,email,avatar_url',
                    'userB:id,name,email,avatar_url',
                    'messages' => fn($q) => $q->latest()->limit(1),
                ])
                ->where(function ($query) use ($user) {
                    $query->where('user_a_id', $user->id)
                          ->orWhere('user_b_id', $user->id);
                })
                ->latest('last_message_at')
                ->get();

                $totalUnread = 0;
                $mapped = $conversations->map(function($conv) use ($user, &$totalUnread) {
                    $otherUser = $user->id === $conv->user_a_id ? $conv->userB : $conv->userA;
                    
                    $lastMessage = $conv->messages->first();
                    
                    // Count unread for this conversation
                    $unreadCount = Message::where('conversation_id', $conv->id)
                        ->where('sender_id', '!=', $user->id)
                        ->where('is_read', false)
                        ->count();
                    
                    $totalUnread += $unreadCount;
                    
                    return [
                        'id' => $conv->id,
                        'other_user' => [
                            'id' => $otherUser?->id,
                            'name' => $otherUser?->name,
                            'email' => $otherUser?->email,
                            'avatar_url' => $otherUser?->avatar_url,
                        ],
                        'last_message' => $lastMessage ? [
                            'id' => $lastMessage->id,
                            'body' => $lastMessage->body,
                            'sender_id' => $lastMessage->sender_id,
                            'created_at' => $lastMessage->created_at->toIso8601String(),
                        ] : null,
                        'last_message_at' => $conv->last_message_at?->toIso8601String(),
                        'unread_count' => $unreadCount,
                    ];
                });

                return response()->json([
                    'success' => true,
                    'conversations' => $mapped,
                    'unread_count' => $totalUnread,
                ]);
            }

            // Return view for regular page requests (with pagination)
            $conversations = Conversation::with([
                'userA:id,name,email',
                'userB:id,name,email',
            ])
            ->where(function ($query) use ($user) {
                $query->where('user_a_id', $user->id)
                      ->orWhere('user_b_id', $user->id);
            })
            ->latest('last_message_at')
            ->paginate(15);

            // Return appropriate view based on user role
            $view = $user->isAdmin() ? 'chat.admin' : 'chat.index';
            
            return view($view, [
                'conversations' => $conversations,
            ]);
        } catch (\Exception $e) {
            \Log::error('ChatController@index error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            
            if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في تحميل المحادثات',
                ], 500);
            }
            
            throw $e;
        }
    }

    /**
     * Start or get a conversation with a specific user.
     */
    public function startChat(User $user)
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

        // Return JSON if AJAX request (for popup chat)
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'conversation' => [
                    'id' => $conversation->id,
                    'messages' => $messages->map(fn($m) => [
                        'id' => $m->id,
                        'sender_id' => $m->sender_id,
                        'body' => $m->body,
                        'attachment_url' => $m->attachment_url,
                        'attachment_type' => $m->attachment_type,
                        'is_read' => $m->is_read,
                        'created_at' => $m->created_at->toIso8601String(),
                    ]),
                ],
                'otherUser' => [
                    'id' => $conversation->getOtherUser($currentUser->id)->id,
                    'name' => $conversation->getOtherUser($currentUser->id)->name,
                    'email' => $conversation->getOtherUser($currentUser->id)->email,
                    'avatar_url' => $conversation->getOtherUser($currentUser->id)->avatar_url,
                ],
            ]);
        }

        // Return view for full page
        return view('chat.show', [
            'conversation' => $conversation,
            'otherUser' => $conversation->getOtherUser($currentUser->id),
            'messages' => $messages,
        ]);
    }

    /**
     * Start chat via JSON request (for popup chat).
     * Accepts user_id in JSON body instead of route parameter.
     */
    public function startChatJson(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $currentUser = auth()->user();
        $otherUserId = $validated['user_id'];

        // Prevent self-chat
        if ($otherUserId === $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot chat with yourself',
            ], 403);
        }

        $otherUser = User::findOrFail($otherUserId);

        // Find or create conversation
        $conversation = Conversation::where(function ($query) use ($currentUser, $otherUserId) {
            $query->where('user_a_id', $currentUser->id)
                  ->where('user_b_id', $otherUserId);
        })->orWhere(function ($query) use ($currentUser, $otherUserId) {
            $query->where('user_a_id', $otherUserId)
                  ->where('user_b_id', $currentUser->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_a_id' => $currentUser->id,
                'user_b_id' => $otherUserId,
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

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'messages' => $messages->map(fn($m) => [
                    'id' => $m->id,
                    'sender_id' => $m->sender_id,
                    'body' => $m->body,
                    'attachment_url' => $m->attachment_url,
                    'attachment_type' => $m->attachment_type,
                    'is_read' => $m->is_read,
                    'created_at' => $m->created_at->toIso8601String(),
                ]),
            ],
            'otherUser' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email,
                'avatar_url' => $otherUser->avatar_url,
            ],
        ]);
    }

    /**
     * Show conversation with specific user.
     */
    public function show(Conversation $conversation)
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

        // Return JSON if AJAX request (for popup chat)
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'conversation' => [
                    'id' => $conversation->id,
                    'messages' => $conversation->messages->map(fn($m) => [
                        'id' => $m->id,
                        'sender_id' => $m->sender_id,
                        'body' => $m->body,
                        'attachment_url' => $m->attachment_url,
                        'attachment_type' => $m->attachment_type,
                        'is_read' => $m->is_read,
                        'created_at' => $m->created_at->toIso8601String(),
                    ]),
                ],
                'otherUser' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'email' => $otherUser->email,
                    'avatar_url' => $otherUser->avatar_url,
                ],
            ]);
        }

        // Return view for full page
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
            'avatar_url' => $message->sender->avatar_url ?? null,
        ];

        // Get the other user (receiver) in the conversation
        $receiverId = $conversation->getOtherUser($userId)?->id;

        // Broadcast message to conversation channel and receiver's notification channel
        broadcast(new MessageSent($conversation->id, $messageData, $senderData, $receiverId))->toOthers();

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

    // ──────────────────────────────────────────────────────────────
    // POPUP CHAT API ENDPOINTS
    // ──────────────────────────────────────────────────────────────

    /**
     * Get unread message count for badge display
     */
    public function unreadCount(): JsonResponse
    {
        $userId = auth()->id();
        
        // Count unread messages where user is a participant
        $count = Message::whereHas('conversation', function ($q) use ($userId) {
            $q->where(function ($query) use ($userId) {
                $query->where('user_a_id', $userId)
                      ->orWhere('user_b_id', $userId);
            });
        })
        ->where('sender_id', '!=', $userId)
        ->where('is_read', false)
        ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}
