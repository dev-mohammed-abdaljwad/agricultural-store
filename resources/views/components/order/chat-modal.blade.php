{{-- Chat Modal Component --}}
@props(['order'])

@php
    $currentUserId = Auth::id();
    // Find or create conversation
    $conversation = \App\Models\Conversation::where(function ($q) use ($currentUserId) {
        $q->where('user_a_id', $currentUserId)
          ->where('user_b_id', 1);
    })->orWhere(function ($q) use ($currentUserId) {
        $q->where('user_a_id', 1)
          ->where('user_b_id', $currentUserId);
    })->first();
    
    $conversationId = $conversation?->id;
    $messages = $conversation?->messages ?? collect();
@endphp

<!-- Chat Modal Button -->
<button 
    onclick="openChatModal()"
    class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-primary text-on-primary shadow-lg hover:shadow-xl transition-all active:scale-95 flex items-center justify-center"
    title="التواصل مع الإدارة">
    <span class="material-symbols-outlined text-xl sm:text-2xl">chat</span>
</button>

<!-- Chat Modal -->
<div id="chatModal" 
    class="hidden fixed inset-0 z-40 flex items-end sm:items-center justify-center p-3 sm:p-4 bg-black/50"
    data-conversation-id="{{ $conversationId }}"
    data-order-id="{{ $order->id }}">
    
    <!-- Modal Content -->
    <div class="bg-surface-container-lowest rounded-lg sm:rounded-2xl w-full max-w-xs sm:max-w-md h-screen sm:h-auto sm:max-h-[600px] flex flex-col shadow-2xl">
        
        <!-- Modal Header -->
        <div class="bg-primary text-on-primary p-3 sm:p-6 rounded-t-lg sm:rounded-t-2xl flex items-center justify-between flex-shrink-0">
            <h3 class="text-base sm:text-lg md:text-xl font-bold font-headline flex items-center gap-2">
                <span class="material-symbols-outlined text-lg sm:text-2xl">support_agent</span>
                التواصل مع الإدارة
            </h3>
            <button 
                onclick="closeChatModal()"
                class="p-1 hover:bg-on-primary/20 rounded-full transition-colors">
                <span class="material-symbols-outlined text-lg sm:text-2xl">close</span>
            </button>
        </div>
        
        <!-- Messages Area -->
        <div id="chatMessages" class="flex-1 overflow-y-auto p-3 sm:p-4 space-y-3 sm:space-y-4">
            @if($messages->isEmpty())
                <div data-empty-state class="h-full flex items-center justify-center text-center text-on-surface-variant">
                    <div>
                        <span class="material-symbols-outlined text-3xl sm:text-4xl block mb-2">chat_bubble_outline</span>
                        <p class="text-xs sm:text-sm">لا توجد رسائل حتى الآن</p>
                        <p class="text-xs mt-1">ابدأ المحادثة مع الإدارة</p>
                    </div>
                </div>
            @else
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id === Auth::id() ? 'flex-row-reverse' : 'flex-row' }} gap-2 sm:gap-3">
                        <!-- Avatar -->
                        <div class="{{ $message->sender_id === Auth::id() ? 'bg-primary' : 'bg-secondary' }} w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center flex-shrink-0 text-on-primary text-xs sm:text-sm">
                            <span class="material-symbols-outlined text-xs sm:text-sm">
                                {{ $message->sender_id === Auth::id() ? 'person' : 'support_agent' }}
                            </span>
                        </div>
                        
                        <!-- Message -->
                        <div class="flex-1 min-w-0">
                            <div class="{{ $message->sender_id === Auth::id() ? 'bg-primary text-on-primary' : 'bg-surface-container-high' }} p-2 sm:p-3 rounded-lg max-w-xs break-words text-xs sm:text-sm">
                                <p>{{ $message->body }}</p>
                            </div>
                            <p class="text-xs text-on-surface-variant mt-1">{{ $message->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        
        <!-- Message Input -->
        <div class="border-t border-outline-variant p-3 sm:p-4 flex gap-2 flex-shrink-0">
            <input 
                id="messageInput"
                type="text" 
                placeholder="اكتب رسالتك..."
                class="flex-1 px-2 sm:px-3 py-2 bg-surface-container rounded-lg border border-outline-variant focus:border-primary outline-none text-xs sm:text-sm transition-colors"
                @keydown.enter="sendChatMessage()">
            <button 
                id="chatSendBtn"
                onclick="sendChatMessage()"
                class="px-3 sm:px-4 py-2 bg-primary text-on-primary rounded-lg hover:opacity-90 transition-all active:scale-95 flex-shrink-0">
                <span class="material-symbols-outlined text-base sm:text-lg">send</span>
            </button>
        </div>
    </div>
</div>

<script>
const CURRENT_USER_ID = {{ Auth::id() }};
let chatConversationId = {{ $conversationId ?? 'null' }};
let chatPusherChannel = null;

function openChatModal() {
    document.getElementById('chatModal').classList.remove('hidden');
    document.getElementById('messageInput').focus();
    scrollChatToBottom();
    
    // Set up Pusher listeners if conversation exists
    if (chatConversationId) {
        setupPusherListener();
    }
}

function closeChatModal() {
    document.getElementById('chatModal').classList.add('hidden');
    // Unsubscribe from Pusher
    if (chatPusherChannel && window.pusher) {
        window.pusher.unsubscribe(`private-conversation_${chatConversationId}`);
        chatPusherChannel = null;
    }
}

function scrollChatToBottom() {
    setTimeout(() => {
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }, 100);
}

// Set up Pusher real-time listener for incoming messages from admin
function setupPusherListener() {
    if (!window.pusher || !chatConversationId) {
        console.warn('[ChatModal] Pusher not available or conversation not set');
        return;
    }
    
    // Subscribe to conversation channel for real-time updates
    chatPusherChannel = window.pusher.subscribe(`private-conversation_${chatConversationId}`);
    
    if (!chatPusherChannel) {
        console.error('[ChatModal] Failed to subscribe to conversation channel');
        return;
    }
    
    // Listen for incoming messages
    chatPusherChannel.bind('message.sent', function(payload) {
        try {
            const data = payload.message || payload;
            
            // Only add message if it's from the admin (not our own message that we just sent)
            if (data.sender_id !== CURRENT_USER_ID) {
                appendMessageToChat({
                    id: data.id,
                    sender_id: data.sender_id,
                    body: data.body,
                    sender_type: data.sender_type,
                    created_at: data.created_at,
                    isIncoming: true
                });
                
                // Show notification badge if modal is closed
                if (document.getElementById('chatModal').classList.contains('hidden')) {
                    console.log('New message from admin');
                }
            }
        } catch (error) {
            console.error('[ChatModal] Error handling incoming message:', error);
        }
    });
    
    console.log(`[ChatModal] Pusher listener set up for conversation ${chatConversationId}`);
}

function appendMessageToChat(params = {}) {
    const {
        id = null,
        sender_id = null,
        body = '',
        sender_type = 'customer',
        created_at = new Date().toIso8601String(),
        isIncoming = false
    } = params;
    
    const chatMessages = document.getElementById('chatMessages');
    
    // Remove empty state if exists
    const emptyState = chatMessages.querySelector('[data-empty-state]');
    if (emptyState) {
        emptyState.remove();
    }
    
    // Create message element
    const isFromCurrentUser = sender_id === CURRENT_USER_ID;
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isFromCurrentUser ? 'flex-row-reverse' : 'flex-row'} gap-3`;
    messageDiv.dataset.messageId = id;
    
    const date = new Date(created_at);
    const timeStr = date.toLocaleTimeString('ar-EG', {hour: '2-digit', minute: '2-digit'});
    
    messageDiv.innerHTML = `
        <div class="${isFromCurrentUser ? 'bg-primary' : 'bg-secondary'} w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-on-primary text-sm">
            <span class="material-symbols-outlined text-sm">
                ${isFromCurrentUser ? 'person' : 'support_agent'}
            </span>
        </div>
        <div class="flex-1">
            <div class="${isFromCurrentUser ? 'bg-primary text-on-primary' : 'bg-surface-container-high'} p-3 rounded-lg max-w-xs break-words text-sm">
                <p>${escapeHtml(body)}</p>
            </div>
            <p class="text-xs text-on-surface-variant mt-1">${timeStr}</p>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
    scrollChatToBottom();
}

function sendChatMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) {
        input.focus();
        return;
    }
    
    if (message.length > 1000) {
        alert('الرسالة طويلة جداً (الحد الأقصى 1000 حرف)');
        return;
    }
    
    // Disable button while sending
    const sendBtn = document.getElementById('chatSendBtn');
    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.style.opacity = '0.5';
    }
    
    const orderId = document.getElementById('chatModal').dataset.orderId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    fetch(`/orders/${orderId}/messages`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => {
        // Get response text first to handle all cases
        return response.text().then(text => {
            // Try to parse as JSON
            let data;
            try {
                data = text ? JSON.parse(text) : {};
            } catch (e) {
                console.error('Failed to parse response:', text);
                throw new Error('Invalid response from server');
            }
            
            // Return both response and data
            return { response, data, status: response.status };
        });
    })
    .then(({ response, data, status }) => {
        if (!response.ok) {
            throw new Error(data.error || `HTTP error! status: ${status}`);
        }
        
        if (data.success) {
            // Update conversation ID if it was just created
            if (!chatConversationId && data.message.conversation_id) {
                chatConversationId = data.message.conversation_id;
                document.getElementById('chatModal').dataset.conversationId = chatConversationId;
                setupPusherListener();
            }
            
            // Add message to chat immediately
            appendMessageToChat({
                id: data.message?.id,
                sender_id: data.message?.sender_id,
                body: message,
                sender_type: 'customer',
                created_at: data.message?.created_at || new Date().toISOString(),
                isIncoming: false
            });
            
            input.value = '';
        } else {
            throw new Error(data.error || 'Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في إرسال الرسالة: ' + error.message);
    })
    .finally(() => {
        // Re-enable button
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.style.opacity = '1';
        }
    });
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking outside
document.addEventListener('click', (e) => {
    const modal = document.getElementById('chatModal');
    if (e.target === modal) {
        closeChatModal();
    }
});

// Allow Enter key to send message
const messageInput = document.getElementById('messageInput');
if (messageInput) {
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendChatMessage();
        }
    });
}
</script>
