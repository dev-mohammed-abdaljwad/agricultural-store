@extends('layouts.chat')

@section('chat-content')
<div class="h-full overflow-hidden flex flex-col" dir="rtl">
    <!-- Mobile Toggle Button (hidden on desktop) -->
    <button id="sidebarToggleBtn" class="fixed top-3 left-3 sm:top-4 sm:left-4 z-50 md:hidden bg-primary text-white p-1.5 sm:p-2 rounded-lg">
        <i class="material-symbols-outlined text-lg">menu</i>
    </button>

    <div class="w-full mx-auto px-2 sm:px-3 md:px-4 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-6 flex-1 overflow-hidden flex flex-col">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-0 sm:gap-3 sm:gap-4 md:gap-6 h-full min-h-0">
            <!-- Order Info Sidebar (toggleable on mobile) -->
            <div id="orderSidebar" class="hidden sm:fixed inset-y-0 right-0 md:block md:relative md:inset-auto z-40 w-64 sm:w-80 md:w-auto md:z-0 bg-white rounded-none md:rounded-2xl border-l md:border-l md:border-surface-200 shadow-lg md:shadow-none transition-transform duration-300 -translate-x-full sm:-translate-x-full md:translate-x-0 md:col-span-1 overflow-y-auto md:overflow-visible">
                <!-- Close button for mobile -->
                <button id="sidebarCloseBtn" class="md:hidden absolute top-3 right-3 text-surface-600 hover:text-surface-900 z-10">
                    <i class="material-symbols-outlined">close</i>
                </button>

                <div class="bg-white rounded-lg md:rounded-2xl border border-surface-200 p-3 sm:p-4 md:p-6 shadow-xs">
                    <h3 class="font-bold text-sm sm:text-base md:text-lg text-surface-900 mb-3 sm:mb-4 mt-10 md:mt-0">{{ __('messages.customer_info') }}</h3>

                    <div class="mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-full bg-primary flex items-center justify-center text-white text-lg sm:text-xl md:text-2xl font-bold mb-2 sm:mb-3 mx-auto">
                            {{ substr($conversation->customer->name, 0, 1) }}
                        </div>
                        <p class="text-center font-semibold text-xs sm:text-sm md:text-base text-surface-900">{{ $conversation->customer->name }}</p>
                        <p class="text-center text-xs text-surface-500 mt-1 sm:mt-2 break-words">{{ $conversation->customer->email }}</p>
                        @if($conversation->customer->phone)
                            <p class="text-center text-xs text-surface-500">{{ $conversation->customer->phone }}</p>
                        @endif
                    </div>

                    <hr class="border-surface-200 mb-4 sm:mb-6">

                    <!-- Order Summary -->
                    <h4 class="font-semibold text-xs sm:text-sm md:text-base text-surface-900 mb-2 sm:mb-3">{{ __('messages.order_summary') }}</h4>
                    <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <span class="text-surface-600">{{ __('messages.status') }}:</span>
                            <span class="font-semibold truncate">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-600">{{ __('messages.total') }}:</span>
                            <span class="font-semibold">{{ $order->total_amount ?? 0 }} {{ __('messages.egp') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-600">{{ __('messages.date') }}:</span>
                            <span class="text-surface-700">{{ $order?->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.orders.show', $order) }}" class="block mt-4 sm:mt-6 w-full text-center px-3 sm:px-4 py-2 bg-primary/10 text-primary rounded-lg text-xs sm:text-sm hover:bg-primary/20 transition font-medium">
                        {{ __('messages.view_order') }}
                    </a>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="md:col-span-2 lg:col-span-3 flex flex-col h-full min-h-0">
                <!-- Title Header -->
                <div class="mb-3 sm:mb-4">
                    <h2 class="text-base sm:text-lg md:text-xl font-bold text-surface-900">{{ __('messages.order') }} #{{ $order->order_number }}</h2>
                    <p class="text-xs sm:text-sm text-surface-500 truncate">{{ __('messages.with') }} {{ $conversation->customer->name }}</p>
                </div>

                <!-- Messages Container -->
                <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl border border-surface-200 shadow-sm md:shadow-md overflow-hidden flex flex-col flex-1 min-h-0">
                    <!-- Messages Area -->
                    <div id="messagesContainer" class="flex-1 overflow-y-auto p-2 sm:p-3 md:p-6 bg-surface-50 space-y-2 sm:space-y-3 md:space-y-4">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs sm:max-w-sm md:max-w-md">
                                    <div class="flex items-end {{ $message->sender_type === 'admin' ? 'flex-row-reverse' : 'flex-row' }} gap-1 sm:gap-2">
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 rounded-full {{ $message->sender_type === 'admin' ? 'bg-primary' : 'bg-amber-500' }} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                            {{ substr($message->sender->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="mb-1 px-2 sm:px-3 text-xs text-surface-500">
                                                <span class="truncate">{{ $message->sender->name }}</span>
                                                @if($message->sender_type === 'admin')
                                                    <span class="text-primary font-semibold ml-1 sm:ml-2">{{ __('messages.admin') }}</span>
                                                @endif
                                            </div>
                                            @if($message->body)
                                                <div class="px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl md:rounded-2xl {{ $message->sender_type === 'admin' ? 'bg-primary text-white' : 'bg-surface-200' }}">
                                                    <p class="text-xs sm:text-sm break-words">{{ $message->body }}</p>
                                                </div>
                                            @endif

                                            @if($message->attachment_url)
                                                <div class="mt-1 sm:mt-2">
                                                    @if($message->attachment_type === 'image')
                                                        <a href="{{ $message->attachment_url }}" target="_blank" class="block">
                                                            <img src="{{ $message->attachment_url }}" alt="Attachment" class="max-w-full rounded-lg md:rounded-xl max-h-48 sm:max-h-56 md:max-h-64 cursor-pointer hover:opacity-90 transition">
                                                        </a>
                                                    @else
                                                        <a href="{{ $message->attachment_url }}" target="_blank" class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 bg-surface-100 rounded-lg text-xs sm:text-sm hover:bg-surface-200 transition">
                                                            <i class="material-symbols-outlined text-sm sm:text-lg">description</i>
                                                            <span>{{ __('messages.download') }}</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            <p class="text-xs text-surface-400 mt-1 px-2 sm:px-3">
                                                {{ $message->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center px-4">
                                <i class="material-symbols-outlined text-4xl sm:text-5xl text-surface-300 mb-2 sm:mb-3">chat_bubble_outline</i>
                                <p class="text-xs sm:text-sm text-surface-500">{{ __('messages.no_messages') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Input Area -->
                    <div class="border-t border-surface-200 bg-white p-2 sm:p-3 md:p-4">
                        <form id="sendMessageForm" class="flex gap-2">
                            @csrf
                            <input
                                type="text"
                                id="messageInput"
                                name="body"
                                placeholder="{{ __('messages.type_response') }}"
                                class="flex-1 px-2 sm:px-3 md:px-4 py-2 md:py-3 text-xs sm:text-sm bg-surface-100 rounded-full border border-transparent focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition"
                            >
                            <label for="attachmentInput" class="flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 md:w-12 md:h-12 bg-surface-100 rounded-full cursor-pointer hover:bg-surface-200 transition flex-shrink-0">
                                <i class="material-symbols-outlined text-primary text-lg sm:text-xl">add_a_photo</i>
                                <input type="file" id="attachmentInput" name="attachment" class="hidden" accept="image/*,.pdf,.doc,.docx">
                            </label>
                            <button type="submit" class="flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 md:w-12 md:h-12 bg-primary text-white rounded-full hover:bg-primary-dark transition flex-shrink-0">
                                <i class="material-symbols-outlined text-lg sm:text-xl">send</i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Replies Bar (responsive grid) -->
                <div class="mt-3 sm:mt-4 md:mt-6 bg-white rounded-lg sm:rounded-xl md:rounded-2xl border border-surface-200 p-3 sm:p-4 md:p-6 shadow-xs">
                    <p class="text-xs sm:text-sm font-semibold text-surface-900 mb-2 sm:mb-3">{{ __('messages.quick_replies') }}</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="insertQuickReply('{{ __('messages.quick_hello') }}')" class="px-2 sm:px-3 md:px-4 py-1 sm:py-2 bg-primary/10 text-primary rounded-full text-xs sm:text-sm hover:bg-primary/20 transition whitespace-nowrap">
                            {{ __('messages.quick_hello') }}
                        </button>
                        <button type="button" onclick="insertQuickReply('{{ __('messages.quick_checking') }}')" class="px-2 sm:px-3 md:px-4 py-1 sm:py-2 bg-primary/10 text-primary rounded-full text-xs sm:text-sm hover:bg-primary/20 transition whitespace-nowrap">
                            {{ __('messages.quick_checking') }}
                        </button>
                        <button type="button" onclick="insertQuickReply('{{ __('messages.quick_ready') }}')" class="px-2 sm:px-3 md:px-4 py-1 sm:py-2 bg-primary/10 text-primary rounded-full text-xs sm:text-sm hover:bg-primary/20 transition whitespace-nowrap">
                            {{ __('messages.quick_ready') }}
                        </button>
                        <button type="button" onclick="insertQuickReply('{{ __('messages.quick_help') }}')" class="px-2 sm:px-3 md:px-4 py-1 sm:py-2 bg-primary/10 text-primary rounded-full text-xs sm:text-sm hover:bg-primary/20 transition whitespace-nowrap">
                            {{ __('messages.quick_help') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const conversationId = {{ $conversation->id }};
    const currentUserId = {{ auth()->id() }};
    const messagesContainer = document.getElementById('messagesContainer');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const orderSidebar = document.getElementById('orderSidebar');

    // Mobile sidebar toggle - handle hidden + translate states
    sidebarToggleBtn?.addEventListener('click', () => {
        const isHidden = orderSidebar.classList.contains('hidden');
        const isTranslated = orderSidebar.classList.contains('-translate-x-full');
        
        // On mobile (hidden) - show it
        if (isHidden) {
            orderSidebar.classList.remove('hidden');
        } 
        // On small screens (visible but translated) - toggle translate
        else {
            orderSidebar.classList.toggle('-translate-x-full');
        }
    });

    sidebarCloseBtn?.addEventListener('click', () => {
        const isHidden = orderSidebar.classList.contains('hidden');
        
        if (isHidden) {
            orderSidebar.classList.add('hidden');
        } else {
            orderSidebar.classList.add('-translate-x-full');
        }
    });

    // Detect fullscreen changes and hide sidebar if not in fullscreen
    function updateSidebarVisibility() {
        const isFullscreen = document.fullscreenElement || 
                            document.webkitFullscreenElement || 
                            document.mozFullScreenElement || 
                            document.msFullscreenElement;
        
        // If not in fullscreen, hide the sidebar on mobile
        if (!isFullscreen) {
            if (window.innerWidth < 640) { // xs screens
                orderSidebar?.classList.add('hidden');
            } else {
                orderSidebar?.classList.add('-translate-x-full');
            }
        } else {
            // In fullscreen - show sidebar
            orderSidebar?.classList.remove('hidden');
            orderSidebar?.classList.remove('-translate-x-full');
        }
    }

    // Listen for fullscreen changes
    document.addEventListener('fullscreenchange', updateSidebarVisibility);
    document.addEventListener('webkitfullscreenchange', updateSidebarVisibility);
    document.addEventListener('mozfullscreenchange', updateSidebarVisibility);
    document.addEventListener('MSFullscreenChange', updateSidebarVisibility);

    // Initial check - hide sidebar if not in fullscreen on page load
    updateSidebarVisibility();

    // Close sidebar when clicking outside on mobile/small screens
    document.addEventListener('click', (e) => {
        if (window.innerWidth < 640) {
            // If click is outside sidebar and not on toggle button
            if (!orderSidebar.contains(e.target) && !sidebarToggleBtn.contains(e.target)) {
                orderSidebar.classList.add('hidden');
            }
        } else if (window.innerWidth < 768) {
            // Small screens: use translate instead
            if (!orderSidebar.contains(e.target) && !sidebarToggleBtn.contains(e.target)) {
                orderSidebar.classList.add('-translate-x-full');
            }
        }
    });

    // Helper function to wait for Pusher to be initialized
    function waitForPusher(callback, maxAttempts = 20) {
        if (typeof window.pusher !== 'undefined') {
            callback();
        } else {
            if (maxAttempts > 0) {
                console.log('[AdminChat] Waiting for Pusher... (attempts left: ' + maxAttempts + ')');
                setTimeout(() => waitForPusher(callback, maxAttempts - 1), 250);
            } else {
                console.error('[AdminChat] Pusher not initialized after timeout');
            }
        }
    }

    // Subscribe to conversation channel via Pusher (already initialized in layout)
    @if(config('broadcasting.default') === 'pusher')
    waitForPusher(() => {
        const channel = window.pusher.subscribe('private-conversation_' + conversationId);
        
        channel.bind('message.sent', (data) => {
            // Don't add our own messages (already added from form submission)
            if (data.sender.id !== currentUserId) {
                appendMessage(data.message, data.sender);
            }
        });
        
        channel.bind('pusher:subscription_error', (error) => {
            console.error('[AdminChat] Subscription error:', error);
        });
        
        console.log('[AdminChat] ✅ Subscribed to conversation');
    });
    @endif

    // Send message
    document.getElementById('sendMessageForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const body = document.getElementById('messageInput').value.trim();
        const attachmentInput = document.getElementById('attachmentInput');
        const attachment = attachmentInput?.files[0];

        if (!body && !attachment) {
            showError('{{ __('messages.message_empty') }}');
            return;
        }

        try {
            // Build FormData with only non-empty fields
            const formData = new FormData();
            formData.append('_token', document.querySelector('[name="_token"]').value);
            
            // Only append body if it has content
            if (body) {
                formData.append('body', body);
            }
            
            // Only append attachment if file is selected
            if (attachment) {
                formData.append('attachment', attachment);
            }
            
            const response = await fetch(`/conversations/{{ $conversation->id }}/messages`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                console.error('Server responded with status:', response.status);
                console.error('Response data:', data);
                showError(data.message || '{{ __('messages.send_error') }}');
                return;
            }

            if (data.success) {
                document.getElementById('messageInput').value = '';
                document.getElementById('attachmentInput').value = '';
                appendMessage(data.message, data.message.sender);
            } else {
                showError(data.message || '{{ __('messages.send_error') }}');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            showError('{{ __('messages.send_error') }}');
        }
    });

    function appendMessage(message, sender) {
        const isAdminMessage = message.sender_type === 'admin';
        const div = document.createElement('div');
        div.className = `flex ${isAdminMessage ? 'justify-end' : 'justify-start'}`;

        let attachmentHTML = '';
        if (message.attachment_url) {
            if (message.attachment_type === 'image') {
                attachmentHTML = `
                    <a href="${message.attachment_url}" target="_blank" class="block mt-2">
                        <img src="${message.attachment_url}" alt="Attachment" class="max-w-full rounded-xl max-h-64 cursor-pointer hover:opacity-90 transition">
                    </a>
                `;
            } else {
                attachmentHTML = `
                    <a href="${message.attachment_url}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-100 rounded-lg hover:bg-surface-200 transition mt-2">
                        <i class="material-symbols-outlined text-lg">description</i>
                        <span class="text-sm">{{ __('messages.download') }}</span>
                    </a>
                `;
            }
        }

        const bodyHTML = message.body ? `<div class="px-4 py-3 rounded-2xl ${isAdminMessage ? 'bg-primary text-white' : 'bg-surface-200'}"><p class="text-sm break-words">${escapeHtml(message.body)}</p></div>` : '';

        div.innerHTML = `
            <div class="max-w-md">
                <div class="flex items-end ${isAdminMessage ? 'flex-row-reverse' : 'flex-row'} gap-2">
                    <div class="w-8 h-8 rounded-full ${isAdminMessage ? 'bg-primary' : 'bg-amber-500'} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        ${sender.name.charAt(0)}
                    </div>
                    <div>
                        <div class="mb-1 px-3 text-xs text-surface-500">
                            ${sender.name}
                            ${isAdminMessage ? '<span class="text-primary font-semibold ml-2">{{ __('messages.admin') }}</span>' : ''}
                        </div>
                        ${bodyHTML}
                        ${attachmentHTML}
                        <p class="text-xs text-surface-400 mt-2 px-3">
                            ${new Date(message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                        </p>
                    </div>
                </div>
            </div>
        `;

        messagesContainer.appendChild(div);
        scrollToBottom();
    }

    function insertQuickReply(text) {
        document.getElementById('messageInput').value = text;
        document.getElementById('messageInput').focus();
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showError(message) {
        // Show error message (replace with toast if available)
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    function showSuccess(message) {
        // Show success message
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm';
        successDiv.textContent = message;
        document.body.appendChild(successDiv);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            successDiv.remove();
        }, 3000);
    }

    // Scroll to bottom on load
    scrollToBottom();
</script>

<style>
    [dir="rtl"] .flex-row {
        flex-direction: row-reverse;
    }

    [dir="rtl"] .ml-2 {
        margin-left: 0;
        margin-right: 0.5rem;
    }
</style>
@endsection
