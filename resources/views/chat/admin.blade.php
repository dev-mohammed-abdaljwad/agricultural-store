@extends('layouts.admin')

@section('title', 'المحادثات - حصاد')

@section('content')
<!-- Welcome Header -->
<section class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8 md:mb-12">
    <div>
        <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-black font-headline text-primary mb-1 sm:mb-2 tracking-tight">{{ __('messages.support_chat') }}</h2>
        <p class="text-xs sm:text-sm md:text-base text-on-surface-variant max-w-2xl">{{ __('messages.manage_customer_conversations') }}</p>
    </div>
    <div class="flex gap-2 sm:gap-3">
        <a href="{{ route('admin.chat.index') }}" class="px-3 sm:px-4 md:px-6 py-2 md:py-3 bg-primary text-white font-bold text-xs sm:text-sm md:text-base rounded-lg sm:rounded-xl flex items-center gap-2 hover:bg-primary-dark transition-colors flex-shrink-0">
            <span class="material-symbols-outlined text-sm sm:text-base md:text-lg">refresh</span>
            <span>{{ __('messages.refresh') }}</span>
        </a>
    </div>
</section>

<!-- Stats Bento Grid -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8 md:mb-12">
    <!-- Total Conversations -->
    <div class="bg-surface-container-lowest p-3 sm:p-4 md:p-6 rounded-lg sm:rounded-xl md:rounded-2xl flex flex-col justify-between min-h-28 sm:min-h-32 md:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start gap-2">
            <span class="text-xs sm:text-sm text-on-surface-variant font-bold">{{ __('messages.total_conversations') }}</span>
            <div class="p-1.5 sm:p-2 bg-primary-fixed rounded-lg text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-base sm:text-lg md:text-2xl">chat_bubble</span>
            </div>
        </div>
        <div>
            <p class="text-xl sm:text-2xl md:text-3xl font-black font-headline text-primary">{{ $conversations->total() ?? 0 }}</p>
            <p class="text-xs text-on-surface-variant mt-1 font-bold">{{ __('messages.active_chats') }}</p>
        </div>
    </div>

    <!-- Unread Messages -->
    <div class="bg-surface-container-lowest p-3 sm:p-4 md:p-6 rounded-lg sm:rounded-xl md:rounded-2xl flex flex-col justify-between min-h-28 sm:min-h-32 md:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start gap-2">
            <span class="text-xs sm:text-sm text-on-surface-variant font-bold">{{ __('messages.unread_messages') }}</span>
            <div class="p-1.5 sm:p-2 bg-secondary-container rounded-lg text-secondary flex-shrink-0">
                <span class="material-symbols-outlined text-base sm:text-lg md:text-2xl">mail_outline</span>
            </div>
        </div>
        <div>
            <p class="text-xl sm:text-2xl md:text-3xl font-black font-headline text-secondary" id="unreadCount">0</p>
            <p class="text-xs text-red-600 font-bold mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">priority_high</span>
                {{ __('messages.needs_attention') }}
            </p>
        </div>
    </div>

    <!-- New Conversations Today -->
    <div class="bg-surface-container-lowest p-3 sm:p-4 md:p-6 rounded-lg sm:rounded-xl md:rounded-2xl flex flex-col justify-between min-h-28 sm:min-h-32 md:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start gap-2">
            <span class="text-xs sm:text-sm text-on-surface-variant font-bold">{{ __('messages.today_conversations') }}</span>
            <div class="p-1.5 sm:p-2 bg-tertiary-fixed rounded-lg text-tertiary flex-shrink-0">
                <span class="material-symbols-outlined text-base sm:text-lg md:text-2xl">calendar_today</span>
            </div>
        </div>
        <div>
            <p class="text-xl sm:text-2xl md:text-3xl font-black font-headline text-tertiary" id="todayCount">{{ $conversations->count() ?? 0 }}</p>
            <p class="text-xs text-on-surface-variant mt-1 font-bold">{{ __('messages.new_today') }}</p>
        </div>
    </div>

    <!-- Active Customers -->
    <div class="bg-surface-container-lowest p-3 sm:p-4 md:p-6 rounded-lg sm:rounded-xl md:rounded-2xl flex flex-col justify-between min-h-28 sm:min-h-32 md:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start gap-2">
            <span class="text-xs sm:text-sm text-on-surface-variant font-bold">{{ __('messages.active_customers') }}</span>
            <div class="p-1.5 sm:p-2 bg-primary-fixed rounded-lg text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-base sm:text-lg md:text-2xl">people</span>
            </div>
        </div>
        <div>
            <p class="text-xl sm:text-2xl md:text-3xl font-black font-headline text-primary" id="customerCount">{{ $conversations->count() ?? 0 }}</p>
            <p class="text-xs text-green-600 font-bold mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">trending_up</span>
                {{ __('messages.in_conversation') }}
            </p>
        </div>
    </div>
</section>

<!-- Conversations List Section -->
<section class="mb-6 sm:mb-8 md:mb-12">
    <div class="bg-surface-container-lowest rounded-lg sm:rounded-2xl md:rounded-3xl border border-outline-variant/10 overflow-hidden">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4 p-3 sm:p-4 md:p-6 border-b border-outline-variant/10">
            <div>
                <h3 class="text-base sm:text-lg md:text-xl font-bold font-headline text-on-surface">{{ __('messages.active_conversations') }}</h3>
                <p class="text-xs text-on-surface-variant mt-1">{{ __('messages.customer_conversations') }}</p>
            </div>
            <div class="w-full sm:w-auto">
                <input 
                    type="text" 
                    id="conversationSearchAdmin" 
                    placeholder="{{ __('messages.search_conversations') }}"
                    class="w-full px-3 sm:px-4 py-2 md:py-2.5 bg-white border border-outline-variant rounded-lg sm:rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                >
            </div>
        </div>

        <!-- Conversations List -->
        <div class="divide-y divide-outline-variant/10">
            @forelse($conversations as $conversation)
                @php
                    $unreadCount = $conversation->messages()
                        ->where('sender_id', '!=', auth()->id())
                        ->where('is_read', false)
                        ->count();
                    $otherUser = $conversation->getOtherUser(auth()->id());
                @endphp
                
                <a href="{{ route('chat.show', $conversation) }}" class="block group hover:bg-surface-container transition-all duration-200 p-3 sm:p-4 md:p-6 conversation-item" data-search="{{ $otherUser?->name . ' ' . $otherUser?->email }}">
                    <div class="flex items-start justify-between gap-3 sm:gap-4">
                        <!-- User Info & Messages -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-bold text-xs sm:text-sm md:text-base text-on-surface group-hover:text-primary transition truncate">
                                        {{ $otherUser?->name ?? __('messages.unknown') }}
                                    </h4>
                                    <p class="text-xs text-on-surface-variant mt-1 truncate">{{ $otherUser?->email }}</p>
                                </div>
                                @if($unreadCount > 0)
                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full whitespace-nowrap flex-shrink-0 ml-2">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>

                            <!-- Last Message Preview -->
                            @if($conversation->lastMessage)
                                <p class="text-xs sm:text-sm text-on-surface-variant line-clamp-2 mb-2 sm:mb-3 mt-2 sm:mt-3">
                                    <span class="font-medium">{{ $conversation->lastMessage->sender?->name }}:</span>
                                    <span>{{ Str::limit($conversation->lastMessage->body ?? '[' . __('messages.attachment') . ']', 80) }}</span>
                                </p>
                                <div class="flex items-center gap-2 sm:gap-4 text-xs text-on-surface-variant">
                                    <span>{{ $conversation->lastMessage->created_at->format('H:i') }}</span>
                                    <span>{{ $conversation->lastMessage->created_at->format('d M Y') }}</span>
                                </div>
                            @else
                                <p class="text-xs sm:text-sm text-on-surface-variant italic mt-2 sm:mt-3">{{ __('messages.no_messages_yet') }}</p>
                            @endif
                        </div>

                        <!-- Action Arrow -->
                        <span class="material-symbols-outlined text-primary group-hover:translate-x-1 transition mt-1 flex-shrink-0 text-base sm:text-lg">arrow_outward</span>
                    </div>
                </a>
            @empty
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-12 sm:py-16 md:py-20 px-4 sm:px-6">
                    <div class="p-3 sm:p-4 md:p-6 bg-primary-fixed rounded-full mb-3 sm:mb-4">
                        <span class="material-symbols-outlined text-2xl sm:text-3xl md:text-5xl text-primary">chat_bubble_outline</span>
                    </div>
                    <h4 class="text-base sm:text-lg md:text-xl font-bold text-on-surface mb-1 sm:mb-2">{{ __('messages.no_conversations') }}</h4>
                    <p class="text-xs sm:text-sm md:text-base text-on-surface-variant text-center max-w-md">{{ __('messages.no_conv_yet') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($conversations->hasPages())
            <div class="px-3 sm:px-4 md:px-6 py-4 md:py-6 border-t border-outline-variant/10 flex justify-center">
                {{ $conversations->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</section>

<script>
    // Calculate unread count
    function updateUnreadCount() {
        let total = 0;
        document.querySelectorAll('.conversation-item').forEach(item => {
            const badge = item.querySelector('span[class*="bg-red-500"]');
            if (badge) {
                total += parseInt(badge.textContent) || 0;
            }
        });
        document.getElementById('unreadCount').textContent = total;
    }

    // Update customer count
    function updateCustomerCount() {
        const count = document.querySelectorAll('.conversation-item').length;
        document.getElementById('customerCount').textContent = count;
    }

    // Search conversations
    document.getElementById('conversationSearchAdmin')?.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.conversation-item').forEach(item => {
            const searchData = item.getAttribute('data-search')?.toLowerCase() || '';
            const text = item.textContent.toLowerCase();
            item.style.display = (text.includes(searchTerm) || searchData.includes(searchTerm)) ? 'block' : 'none';
        });
    });

    // Initialize
    updateUnreadCount();
    updateCustomerCount();
</script>

@endsection

