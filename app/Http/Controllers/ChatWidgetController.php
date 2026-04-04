<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatWidgetController extends Controller
{
    /**
     * GET /chat
     * Load all conversations for the authenticated user
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        // Customer sees only their conversations
        // Admin sees all conversations
        $query = Conversation::with([
            'lastMessage.sender',
            'customer',
            'order',
        ]);

        if ($user->role === 'customer') {
            $query->where('customer_id', $user->id);
        }

        $conversations = $query
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conv) use ($user) {
                // Safety check: skip if no customer_id (orphaned conversation)
                if (!$conv->customer_id) {
                    return null;
                }

                // Unread = messages from the OTHER side not read yet
                $unreadCount = $conv->messages()
                    ->where('sender_type', $user->role === 'admin' ? 'customer' : 'admin')
                    ->where('is_read', false)
                    ->count();

                // Determine the other user
                $otherUser = ['id' => null, 'name' => 'Unknown'];
                
                if ($user->role === 'admin') {
                    // Admin viewing: show the customer
                    if ($conv->customer && $conv->customer->id) {
                        $otherUser = [
                            'id'   => (int) $conv->customer->id,
                            'name' => (string) $conv->customer->name,
                        ];
                    }
                } else {
                    // Customer viewing: show admin (hardcoded support team)
                    $otherUser = [
                        'id'   => 0,
                        'name' => 'فريق حصاد',
                    ];
                }

                return [
                    'id'           => (int) $conv->id,
                    'order_number' => $conv->order?->order_number ? (string) $conv->order->order_number : null,
                    'unread_count' => (int) $unreadCount,
                    'last_message' => $conv->lastMessage ? [
                        'body'       => (string) ($conv->lastMessage->body ?? ''),
                        'created_at' => $conv->lastMessage->created_at->format('h:i ص/م'),
                        'sender_type'=> (string) ($conv->lastMessage->sender_type ?? 'customer'),
                    ] : null,
                    'other_user' => $otherUser,
                ];
            })
            ->filter();

        $totalUnread = $conversations->sum('unread_count');

        return response()->json([
            'success'      => true,
            'conversations'=> $conversations,
            'unread_count' => $totalUnread,
        ]);
    }

    /**
     * GET /chat/{conversation}/messages
     * Load messages for a conversation (paginated)
     */
    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        // Authorization check
        $this->authorizeConversation($conversation);

        $perPage = min((int) $request->get('per_page', 20), 50);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        // Mark messages as read
        $this->markConversationRead($conversation);

        return response()->json([
            'success'  => true,
            'messages' => [
                'data' => $messages->items(),
                'meta' => [
                    'current_page' => $messages->currentPage(),
                    'last_page'    => $messages->lastPage(),
                    'total'        => $messages->total(),
                ],
            ],
        ]);
    }

    /**
     * POST /chat/{conversation}/messages
     * Send a new message
     */
    public function send(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorizeConversation($conversation);

        // Validate: body required if no attachment, attachment optional
        $validated = $request->validate([
            'body' => 'required_without:attachment|string|max:2000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx',
        ]);

        $user       = Auth::user();
        $senderType = $user->role === 'admin' ? 'admin' : 'customer';

        // Handle file upload
        $attachmentUrl = null;
        $attachmentType = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $isImage = in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif']);

            $path = $file->store("uploads/conversations/{$conversation->id}", 'public');
            $attachmentUrl = asset("storage/{$path}");
            $attachmentType = $isImage ? 'image' : 'file';
            $attachmentName = $file->getClientOriginalName();
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $user->id,
            'sender_type'     => $senderType,
            'body'            => $validated['body'] ?? null,
            'attachment_url'  => $attachmentUrl,
            'attachment_type' => $attachmentType,
            'attachment_name' => $attachmentName,
            'is_read'         => false,
        ]);

        // Update last message timestamp
        $conversation->update(['last_message_at' => now()]);

        // Get the receiver
        $receiver = $conversation->customer_id === $user->id
            ? $conversation->admin
            : $conversation->customer;

        // Broadcast to other party via Pusher
        $messageData = [
            'id'              => $message->id,
            'body'            => $message->body,
            'sender_type'     => $message->sender_type,
            'sender_id'       => $message->sender_id,
            'user_id'         => $message->sender_id,
            'is_read'         => $message->is_read,
            'attachment_url'  => $message->attachment_url,
            'attachment_type' => $message->attachment_type,
            'attachment_name' => $message->attachment_name,
            'created_at'      => $message->created_at->format('Y-m-d H:i:s'),
        ];

        $senderData = [
            'id'   => $user->id,
            'name' => $user->name,
        ];

        broadcast(new MessageSent(
            $conversation->id,
            $messageData,
            $senderData,
            $receiver->id ?? null
        ))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $messageData,
        ]);
    }

    /**
     * POST /chat/{conversation}/read
     * Mark messages as read
     */
    public function markRead(Conversation $conversation): JsonResponse
    {
        $this->authorizeConversation($conversation);
        $this->markConversationRead($conversation);

        return response()->json(['success' => true]);
    }

    /**
     * Authorize: customer can only access their conversation
     * Admin can access all
     */
    private function authorizeConversation(Conversation $conversation): void
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $conversation->customer_id !== $user->id) {
            abort(403, 'غير مصرح لك بالوصول');
        }
    }

    /**
     * Mark opposite side messages as read
     */
    private function markConversationRead(Conversation $conversation): void
    {
        $user           = Auth::user();
        $oppositeSender = $user->role === 'admin' ? 'customer' : 'admin';

        $conversation->messages()
            ->where('sender_type', $oppositeSender)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
