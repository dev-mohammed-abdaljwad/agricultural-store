@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-primary/5 to-white" dir="rtl">
    <!-- Header with Back Button -->
    <div class="sticky top-0 z-40 bg-white border-b border-surface-200 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('chat.index') }}" class="p-2 hover:bg-surface-100 rounded-full transition">
                        <i class="material-symbols-outlined text-2xl text-surface-600">arrow_back</i>
                    </a>
                    <div>
                        <p class="font-semibold text-surface-900">
                            {{ $otherUser->name }}
                        </p>
                        <p class="text-xs text-surface-500">
                            {{ $otherUser->isAdmin() ? __('messages.admin_support') : __('messages.customer') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-xs font-medium text-green-700">{{ __('messages.online') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col h-[calc(100vh-200px)]">
        <!-- Messages Container -->
        <div class="flex-1 overflow-y-auto space-y-6 pb-6" id="messagesContainer">
            @forelse($conversation->messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-start' : 'justify-end' }} group">
                    @if($message->sender_id === auth()->id())
                        <!-- Sent by current user (Left align) -->
                        <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                            <div class="bg-white border border-surface-200 rounded-2xl rounded-tl-sm p-4 shadow-sm">
                                @if($message->body)
                                    <p class="text-surface-900 text-sm leading-relaxed break-words">
                                        {{ $message->body }}
                                    </p>
                                @endif

                                @if($message->attachment_url)
                                    <div class="mt-3">
                                        @if($message->attachment_type === 'image')
                                            <img src="{{ $message->attachment_url }}" alt="Attachment" class="max-w-full rounded-lg">
                                        @else
                                            <a href="{{ $message->attachment_url }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-surface-50 hover:bg-surface-100 rounded-lg transition">
                                                <i class="material-symbols-outlined text-base">download</i>
                                                <span class="text-xs text-surface-700">{{ __('messages.download') }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <p class="text-xs text-surface-400 mt-2">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Sent by other user (Right align) -->
                        <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                            <div class="bg-primary text-white rounded-2xl rounded-tr-sm p-4 shadow-md">
                                @if($message->body)
                                    <p class="text-white text-sm leading-relaxed break-words">
                                        {{ $message->body }}
                                    </p>
                                @endif

                                @if($message->attachment_url)
                                    <div class="mt-3">
                                        @if($message->attachment_type === 'image')
                                            <img src="{{ $message->attachment_url }}" alt="Attachment" class="max-w-full rounded-lg">
                                        @else
                                            <a href="{{ $message->attachment_url }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">
                                                <i class="material-symbols-outlined text-base">download</i>
                                                <span class="text-xs">{{ __('messages.download') }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center justify-between gap-2 mt-2">
                                    <p class="text-xs text-white/70">
                                        {{ $message->created_at->format('H:i') }}
                                    </p>
                                    @if($message->is_read)
                                        <i class="material-symbols-outlined text-xs">done_all</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <i class="material-symbols-outlined text-6xl text-surface-300 mb-4">chat_bubble_outline</i>
                    <h3 class="text-lg font-semibold text-surface-900 mb-2">{{ __('messages.no_messages_yet') }}</h3>
                    <p class="text-surface-500">{{ __('messages.start_conversation') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input Form -->
        <div class="border-t border-surface-200 bg-white rounded-t-2xl p-4">
            <form id="messageForm" class="flex items-end gap-3">
                @csrf

                <label class="cursor-pointer">
                    <input type="file" id="fileInput" class="hidden" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                    <div class="p-2 hover:bg-surface-100 rounded-full transition">
                        <i class="material-symbols-outlined text-surface-600">attach_file</i>
                    </div>
                </label>

                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        name="body" 
                        id="messageInput"
                        placeholder="{{ __('messages.type_message') }}"
                        class="w-full px-4 py-3 bg-surface-50 border border-surface-200 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary resize-none"
                    >
                </div>

                <button 
                    type="submit"
                    class="p-3 bg-primary text-white rounded-full hover:bg-primary-dark transition disabled:opacity-50 disabled:cursor-not-allowed"
                    id="sendBtn"
                >
                    <i class="material-symbols-outlined">send</i>
                </button>
            </form>

            <div id="filePreview" class="mt-3 hidden">
                <p class="text-xs text-surface-500 flex items-center gap-2">
                    <i class="material-symbols-outlined text-sm">attach_file</i>
                    <span id="fileName"></span>
                </p>
            </div>

            <div id="loadingIndicator" class="mt-3 hidden">
                <p class="text-xs text-surface-500 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                    {{ __('messages.sending') }}
                </p>
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
        formData.append('body', body);
        formData.append('_token', token);

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
            alert('{{ __('messages.error_sending') }}');
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
            <div class="flex ${isOwn ? 'justify-start' : 'justify-end'}">
                <div class="max-w-xs lg:max-w-md ${isOwn ? 'bg-white border border-surface-200' : 'bg-primary text-white'} rounded-2xl ${isOwn ? 'rounded-tl-sm' : 'rounded-tr-sm'} p-4 shadow-${isOwn ? 'sm' : 'md'}">
                    ${message.body ? `<p class="text-sm leading-relaxed break-words">${message.body}</p>` : ''}
                    ${attachmentHTML}
                    <p class="text-xs ${isOwn ? 'text-surface-400' : 'text-white/70'} mt-2">${time}</p>
                </div>
            </div>
        `;

        container.innerHTML += messageHTML;
    }

    @if(config('broadcasting.default') === 'pusher')
        Echo.private('conversation.{{ $conversation->id }}')
            .listen('.message.sent', (event) => {
                if (event.sender.id !== {{ auth()->id() }}) {
                    addMessageToDOM(event.message);
                    scrollToBottom();
                }
            });
    @endif
</script>
@endpush

<style>
    [dir="rtl"] #messageInput {
        text-align: right;
    }
</style>
@endsection
