@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-primary/5 to-white" dir="rtl">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-surface-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-surface-900">{{ __('messages.conversations') }}</h1>
                    <p class="text-sm text-surface-500 mt-1">{{ __('messages.your_conversations') }}</p>
                </div>
                @if(auth()->user()->isAdmin())
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <span class="text-sm font-medium text-primary">
                            {{ $conversations->total() }} {{ __('messages.total_conversations') }}
                        </span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($conversations->count() > 0)
            <!-- Conversations List -->
            <div class="space-y-3">
                @foreach($conversations as $conversation)
                    <a href="{{ route('chat.show', $conversation) }}" class="block group">
                        <div class="bg-white rounded-2xl border border-surface-200 p-4 hover:shadow-md hover:border-primary/20 transition-all duration-200">
                            <div class="flex items-start gap-4">
                                <!-- Unread Badge -->
                                @php
                                    $unreadCount = $conversation->messages()
                                        ->where('sender_id', '!=', auth()->id())
                                        ->where('is_read', false)
                                        ->count();
                                @endphp

                                <!-- User Info -->
                                <div class="flex-1 min-w-0">
                                    @php
                                        $otherUser = $conversation->getOtherUser(auth()->id());
                                    @endphp
                                    
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-semibold text-surface-900 truncate">
                                            {{ $otherUser?->name ?? 'Unknown' }}
                                        </p>
                                        @if($unreadCount > 0)
                                            <span class="ml-2 text-xs font-semibold text-white bg-red-500 px-3 py-1 rounded-full">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Last Message Preview -->
                                    @if($conversation->lastMessage)
                                        <p class="text-sm text-surface-600 line-clamp-1 mb-2">
                                            <span class="font-medium">
                                                @if($conversation->lastMessage->sender_id === auth()->id())
                                                    {{ __('messages.you') }}:
                                                @elseif($conversation->lastMessage->sender)
                                                    {{ $conversation->lastMessage->sender->name }}:
                                                @else
                                                    {{ __('messages.user') }}:
                                                @endif
                                            </span>
                                            {{ Str::limit($conversation->lastMessage->body ?? '[' . __('messages.attachment') . ']', 80) }}
                                        </p>
                                        <p class="text-xs text-surface-400">
                                            {{ $conversation->lastMessage->created_at->diffForHumans() }}
                                        </p>
                                    @else
                                        <p class="text-sm text-surface-400 italic">{{ __('messages.no_messages_yet') }}</p>
                                    @endif
                                </div>

                                <!-- Arrow Icon -->
                                <i class="material-symbols-outlined text-primary group-hover:translate-x-1 transition mt-1">arrow_outward</i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($conversations->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $conversations->links('pagination::tailwind') }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20">
                <i class="material-symbols-outlined text-6xl text-surface-300 mb-4">chat_bubble_outline</i>
                <h3 class="text-xl font-semibold text-surface-900 mb-2">{{ __('messages.no_conversations') }}</h3>
                <p class="text-surface-500 max-w-md text-center mb-6">{{ __('messages.no_conversations_desc') }}</p>
                @if(!auth()->user()->isAdmin())
                    <a href="{{ route('chat.start', ['user' => 1]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-full hover:bg-primary-dark transition">
                        <i class="material-symbols-outlined">add</i>
                        <span>{{ __('messages.start_chat') }}</span>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    [dir="rtl"] .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    [dir="rtl"] .group-hover\:translate-x-1:hover {
        transform: translateX(-0.25rem);
    }
</style>
@endsection
