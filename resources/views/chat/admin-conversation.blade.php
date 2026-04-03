@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-primary/5 to-white" dir="rtl">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-surface-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-surface-900">{{ __('messages.order') }} #{{ $order->order_number }}</h1>
                    <p class="text-sm text-surface-500 mt-1">
                        {{ __('messages.with') }} {{ $conversation->customer->name }}
                    </p>
                </div>
                <a href="{{ route('admin.chat.index') }}" class="text-primary hover:text-primary-dark transition">
                    <i class="material-symbols-outlined">arrow_back</i>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Customer Info Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-surface-200 p-6 shadow-xs sticky top-20">
                    <h3 class="font-bold text-surface-900 mb-4">{{ __('messages.customer_info') }}</h3>

                    <div class="mb-6">
                        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center text-white text-2xl font-bold mb-3 mx-auto">
                            {{ substr($conversation->customer->name, 0, 1) }}
                        </div>
                        <p class="text-center font-semibold text-surface-900">{{ $conversation->customer->name }}</p>
                        <p class="text-center text-sm text-surface-500 mt-2">{{ $conversation->customer->email }}</p>
                        @if($conversation->customer->phone)
                            <p class="text-center text-sm text-surface-500">{{ $conversation->customer->phone }}</p>
                        @endif
                    </div>

                    <hr class="border-surface-200 mb-6">

                    <!-- Order Summary -->
                    <h4 class="font-semibold text-surface-900 mb-3">{{ __('messages.order_summary') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-surface-600">{{ __('messages.status') }}:</span>
                            <span class="font-semibold">{{ ucfirst($order->status) }}</span>
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

                    <a href="{{ route('admin.orders.show', $order) }}" class="block mt-6 w-full text-center px-4 py-2 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition font-medium">
                        {{ __('messages.view_order') }}
                    </a>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-surface-200 shadow-md overflow-hidden flex flex-col" style="height: 700px;">
                    <!-- Messages Area -->
                    <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 bg-surface-50 space-y-4">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-md">
                                    <div class="flex items-end {{ $message->sender_type === 'admin' ? 'flex-row-reverse' : 'flex-row' }} gap-2">
                                        <div class="w-8 h-8 rounded-full {{ $message->sender_type === 'admin' ? 'bg-primary' : 'bg-amber-500' }} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                            {{ substr($message->sender->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="mb-1 px-3 text-xs text-surface-500">
                                                {{ $message->sender->name }}
                                                @if($message->sender_type === 'admin')
                                                    <span class="text-primary font-semibold ml-2">{{ __('messages.admin') }}</span>
                                                @endif
                                            </div>
                                            @if($message->body)
                                                <div class="px-4 py-3 rounded-2xl {{ $message->sender_type === 'admin' ? 'bg-primary text-white' : 'bg-surface-200' }}">
                                                    <p class="text-sm break-words">{{ $message->body }}</p>
                                                </div>
                                            @endif

                                            @if($message->attachment_url)
                                                <div class="mt-2">
                                                    @if($message->attachment_type === 'image')
                                                        <a href="{{ $message->attachment_url }}" target="_blank" class="block">
                                                            <img src="{{ $message->attachment_url }}" alt="Attachment" class="max-w-full rounded-xl max-h-64 cursor-pointer hover:opacity-90 transition">
                                                        </a>
                                                    @else
                                                        <a href="{{ $message->attachment_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-100 rounded-lg hover:bg-surface-200 transition">
                                                            <i class="material-symbols-outlined text-lg">description</i>
                                                            <span class="text-sm">{{ __('messages.download') }}</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            <p class="text-xs text-surface-400 mt-2 px-3">
                                                {{ $message->created_at->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center">
                                <i class="material-symbols-outlined text-5xl text-surface-300 mb-3">chat_bubble_outline</i>
                                <p class="text-surface-500">{{ __('messages.no_messages') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Input Area -->
                    <div class="border-t border-surface-200 bg-white p-4">
                        <form id="sendMessageForm" class="flex gap-2">
                            @csrf
                            <input
                                type="text"
                                id="messageInput"
                                name="body"
                                placeholder="{{ __('messages.type_response') }}"
                                class="flex-1 px-4 py-3 bg-surface-100 rounded-full border border-transparent focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition"
                            >
                            <label for="attachmentInput" class="flex items-center justify-center w-12 h-12 bg-surface-100 rounded-full cursor-pointer hover:bg-surface-200 transition">
                                <i class="material-symbols-outlined text-primary">add_a_photo</i>
                                <input type="file" id="attachmentInput" name="attachment" class="hidden" accept="image/*,.pdf,.doc,.docx">
                            </label>
                            <button type="submit" class="flex items-center justify-center w-12 h-12 bg-primary text-white rounded-full hover:bg-primary-dark transition">
                                <i class="material-symbols-outlined">send</i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick Replies Bar -->
                <div class="mt-6 bg-white rounded-2xl border border-surface-200 p-4 shadow-xs">
                    <p class="text-sm font-semibold text-surface-900 mb-3">{{ __('messages.quick_replies') }}</p>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="insertQuickReply('{{ __('messages.quick_hello') }}')" class="px-4 py-2 bg-primary/10 text-primary rounded-full text-sm hover:bg-primary/20 transition">
                            {{ __('messages.quick_hello') }}
                        </button>
                        <button onclick="insertQuickReply('{{ __('messages.quick_checking') }}')" class="px-4 py-2 bg-primary/10 text-primary rounded-full text-sm hover:bg-primary/20 transition">
                            {{ __('messages.quick_checking') }}
                        </button>
                        <button onclick="insertQuickReply('{{ __('messages.quick_ready') }}')" class="px-4 py-2 bg-primary/10 text-primary rounded-full text-sm hover:bg-primary/20 transition">
                            {{ __('messages.quick_ready') }}
                        </button>
                        <button onclick="insertQuickReply('{{ __('messages.quick_help') }}')" class="px-4 py-2 bg-primary/10 text-primary rounded-full text-sm hover:bg-primary/20 transition">
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

    // Initialize Laravel Echo
    @isset($PUSHER_APP_KEY)
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ $PUSHER_APP_KEY }}',
        cluster: '{{ $PUSHER_APP_CLUSTER }}',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
    });

    // Subscribe to conversation channel
    window.Echo.private('conversation.' + conversationId)
        .listen('message.sent', (data) => {
            appendMessage(data.message, data.sender);
        });
    @endif

    // Send message
    document.getElementById('sendMessageForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const body = formData.get('body')?.trim();
        const attachment = formData.get('attachment');

        if (!body && !attachment) {
            alert('{{ __('messages.message_empty') }}');
            return;
        }

        try {
            const response = await fetch(`/admin/conversations/{{ $conversation->id }}/messages`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('messageInput').value = '';
                document.getElementById('attachmentInput').value = '';
                appendMessage(data.message, data.message.sender);
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('{{ __('messages.send_error') }}');
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
