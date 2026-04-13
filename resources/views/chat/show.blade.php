@extends('layouts.chat')

@section('chat-content')
<div class="h-full overflow-hidden flex flex-col" dir="rtl">
    <div class="w-full mx-auto px-2 sm:px-3 md:px-4 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-6 flex-1 overflow-hidden flex flex-col">
        <!-- Title Header -->
        <div class="mb-3 sm:mb-4">
            <div class="flex items-center gap-2 sm:gap-4">
                <div>
                    <p class="font-semibold text-sm sm:text-base md:text-lg text-surface-900">
                        {{ $otherUser->name }}
                    </p>
                    <p class="text-xs text-surface-500">
                        {{ $otherUser->isAdmin() ? __('messages.admin_support') : __('messages.customer') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages container and input area -->
        <div class="flex-1 overflow-hidden flex flex-col gap-2 sm:gap-3">
            <!-- Messages Box -->
            <div class="bg-white rounded-lg sm:rounded-xl md:rounded-2xl border border-surface-200 shadow-sm md:shadow-md overflow-hidden flex flex-col flex-1">
                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto space-y-3 sm:space-y-4 md:space-y-6 pb-3 sm:pb-4 md:pb-6 p-3 sm:p-4 md:p-6" id="messagesContainer">
            @forelse($conversation->messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }} group">
                    @if($message->sender_id === auth()->id())
                        <!-- Sent by current user (Right align - Green) -->
                        <div class="max-w-xxs sm:max-w-xs md:max-w-sm lg:max-w-md xl:max-w-lg">
                            <div class="bg-primary text-white rounded-lg sm:rounded-xl md:rounded-2xl md:rounded-tr-sm p-2 sm:p-3 md:p-4 shadow-sm md:shadow-md">
                                @if($message->body)
                                    <p class="text-white text-xs sm:text-sm md:text-base leading-relaxed break-words">
                                        {{ $message->body }}
                                    </p>
                                @endif

                                @if($message->attachment_url)
                                    <div class="mt-2 sm:mt-3">
                                        @if($message->attachment_type === 'image')
                                            <img src="{{ $message->attachment_url }}" alt="Attachment" class="max-w-full rounded-md sm:rounded-lg">
                                        @else
                                            <a href="{{ $message->attachment_url }}" target="_blank" class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1 sm:py-2 bg-white/20 hover:bg-white/30 rounded-md sm:rounded-lg transition">
                                                <i class="material-symbols-outlined text-sm sm:text-base">download</i>
                                                <span class="text-xs">{{ __('messages.download') }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <p class="text-xs text-white/70 mt-1 sm:mt-2">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Sent by other user (Left align - White) -->
                        <div class="max-w-xxs sm:max-w-xs md:max-w-sm lg:max-w-md xl:max-w-lg">
                            <div class="bg-white border border-surface-200 rounded-lg sm:rounded-xl md:rounded-2xl md:rounded-tl-sm p-2 sm:p-3 md:p-4 shadow-sm">
                                @if($message->body)
                                    <p class="text-surface-900 text-xs sm:text-sm md:text-base leading-relaxed break-words">
                                        {{ $message->body }}
                                    </p>
                                @endif

                                @if($message->attachment_url)
                                    <div class="mt-2 sm:mt-3">
                                        @if($message->attachment_type === 'image')
                                            <img src="{{ $message->attachment_url }}" alt="Attachment" class="max-w-full rounded-md sm:rounded-lg">
                                        @else
                                            <a href="{{ $message->attachment_url }}" target="_blank" class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1 sm:py-2 bg-surface-50 hover:bg-surface-100 rounded-md sm:rounded-lg transition">
                                                <i class="material-symbols-outlined text-sm sm:text-base">download</i>
                                                <span class="text-xs text-surface-700">{{ __('messages.download') }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <p class="text-xs text-surface-400 mt-1 sm:mt-2">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 sm:py-12 md:py-20 text-center px-4">
                    <i class="material-symbols-outlined text-4xl sm:text-5xl md:text-6xl text-surface-300 mb-2 sm:mb-3 md:mb-4">chat_bubble_outline</i>
                    <h3 class="text-base sm:text-lg md:text-xl font-semibold text-surface-900 mb-1 sm:mb-2">{{ __('messages.no_messages_yet') }}</h3>
                    <p class="text-xs sm:text-sm text-surface-500">{{ __('messages.start_conversation') }}</p>
                </div>
            @endforelse
                </div>
            </div>

            <!-- Message Input Form -->
            <div class="border-t border-surface-200 bg-white p-2 sm:p-3 md:p-4">
            <form id="messageForm" class="flex items-end gap-2 sm:gap-3">
                @csrf

                <label class="cursor-pointer flex-shrink-0">
                    <input type="file" id="fileInput" class="hidden" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                    <div class="p-1.5 sm:p-2 hover:bg-surface-100 rounded-full transition">
                        <i class="material-symbols-outlined text-sm sm:text-base text-surface-600">attach_file</i>
                    </div>
                </label>

                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        name="body" 
                        id="messageInput"
                        placeholder="{{ __('messages.type_message') }}"
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm bg-surface-50 border border-surface-200 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary resize-none"
                    >
                </div>

                <button 
                    type="submit"
                    class="p-1.5 sm:p-2 md:p-3 bg-primary text-white rounded-full hover:bg-primary-dark transition disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                    id="sendBtn"
                >
                    <i class="material-symbols-outlined text-sm sm:text-base md:text-lg">send</i>
                </button>
            </form>

            <div id="filePreview" class="mt-2 sm:mt-3 hidden">
                <p class="text-xs text-surface-500 flex items-center gap-1 sm:gap-2">
                    <i class="material-symbols-outlined text-xs sm:text-sm">attach_file</i>
                    <span id="fileName"></span>
                </p>
            </div>

            <div id="loadingIndicator" class="mt-2 sm:mt-3 hidden">
                <p class="text-xs text-surface-500 flex items-center gap-1 sm:gap-2">
                    <span class="inline-block w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                    {{ __('messages.sending') }}
                </p>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }

    document.addEventListener('DOMContentLoaded', scrollToBottom);

    document.getElementById('fileInput').addEventListener('change', (e) => {
        const preview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');

        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileName.textContent = file.name;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });

    document.getElementById('messageForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const messageInput = document.getElementById('messageInput');
        const fileInput = document.getElementById('fileInput');
        const sendBtn = document.getElementById('sendBtn');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const token = document.querySelector('input[name="_token"]').value;

        const body = messageInput.value.trim();

        if (!body && fileInput.files.length === 0) {
            return;
        }

        sendBtn.disabled = true;
        loadingIndicator.classList.remove('hidden');

        const formData = new FormData();
        formData.append('_token', token);

        if (body) {
            formData.append('body', body);
        }

        if (fileInput.files.length > 0) {
            formData.append('attachment', fileInput.files[0]);
        }

        try {
            const response = await fetch('{{ route('chat.sendMessage', $conversation) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            if (!response.ok) {
                throw new Error('Failed to send message');
            }

            const data = await response.json();

            messageInput.value = '';
            fileInput.value = '';
            document.getElementById('filePreview').classList.add('hidden');

            if (data.message) {
                addMessageToDOM(data.message);
                scrollToBottom();
            }

        } catch (error) {
            console.error('Error:', error);
            showError('{{ __('messages.error_sending') }}');
        } finally {
            sendBtn.disabled = false;
            loadingIndicator.classList.add('hidden');
        }
    });

    function addMessageToDOM(message) {
        const container = document.getElementById('messagesContainer');
        const isOwn = message.sender_id === {{ auth()->id() }};

        let attachmentHTML = '';
        if (message.attachment_url) {
            if (message.attachment_type === 'image') {
                attachmentHTML = `<img src="${message.attachment_url}" alt="Attachment" class="max-w-full rounded-lg mt-2">`;
            } else {
                attachmentHTML = `<a href="${message.attachment_url}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition mt-2">
                    <i class="material-symbols-outlined text-base">download</i>
                    <span class="text-xs">{{ __('messages.download') }}</span>
                </a>`;
            }
        }

        const time = new Date(message.created_at).toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });

        const messageHTML = `
            <div class="flex ${isOwn ? 'justify-end' : 'justify-start'}">
                <div class="max-w-xs lg:max-w-md ${isOwn ? 'bg-primary text-white' : 'bg-white border border-surface-200'} rounded-2xl ${isOwn ? 'rounded-tr-sm' : 'rounded-tl-sm'} p-4 shadow-${isOwn ? 'md' : 'sm'}">
                    ${message.body ? `<p class="text-sm leading-relaxed break-words">${message.body}</p>` : ''}
                    ${attachmentHTML}
                    <p class="text-xs ${isOwn ? 'text-white/70' : 'text-surface-400'} mt-2">${time}</p>
                </div>
            </div>
        `;

        container.innerHTML += messageHTML;
    }

    @if(config('broadcasting.default') === 'pusher')
        // Helper function to wait for Pusher to be initialized
        function waitForPusher(callback, maxAttempts = 20) {
            if (typeof window.pusher !== 'undefined') {
                callback();
            } else {
                if (maxAttempts > 0) {
                    console.log('[FullPageChat] Waiting for Pusher... (attempts left: ' + maxAttempts + ')');
                    setTimeout(() => waitForPusher(callback, maxAttempts - 1), 250);
                } else {
                    console.error('[FullPageChat] Pusher not initialized after timeout');
                }
            }
        }

        // Subscribe to conversation channel via Pusher WebSocket (not Echo)
        @if(config('broadcasting.default') === 'pusher')
        waitForPusher(() => {
            const channel = window.pusher.subscribe('private-conversation_{{ $conversation->id }}');
            
            channel.bind('message.sent', (event) => {
                // Don't add our own messages (already added from response)
                if (event.sender.id !== {{ auth()->id() }}) {
                    addMessageToDOM(event.message);
                    scrollToBottom();
                }
            });
            
            channel.bind('pusher:subscription_error', (error) => {
                console.error('[FullPageChat] Subscription error:', error);
            });
            
            console.log('[FullPageChat] ✅ Subscribed to conversation via Pusher');
        });
    @endif
    @endif
</script>
@endpush

<style>
    [dir="rtl"] #messageInput {
        text-align: right;
    }
</style>
@endsection
