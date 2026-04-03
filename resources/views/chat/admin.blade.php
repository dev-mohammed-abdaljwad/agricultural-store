@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-primary/5 to-white" dir="rtl">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-surface-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-surface-900">{{ __('messages.conversations') }}</h1>
                    <p class="text-sm text-surface-500 mt-1">{{ __('messages.admin_conversations_desc') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <span class="text-sm font-medium text-primary">
                            {{ $conversations->total() }} {{ __('messages.total_conversations') }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($conversations->count() > 0)
            <!-- Conversations Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($conversations as $conversation)
                    <a href="{{ route('admin.conversations.show', $conversation) }}" class="group block">
                        <div class="bg-white rounded-2xl border border-surface-200 p-6 shadow-xs hover:shadow-md hover:border-primary/20 transition-all duration-200">
                            <!-- Unread Badge -->
                            @if($conversation->unread_count > 0)
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xs font-semibold text-white bg-red-500 px-3 py-1 rounded-full">
                                        {{ $conversation->unread_count }} {{ __('messages.unread') }}
                                    </span>
                                </div>
                            @endif

                            <!-- Order Info -->
                            <div class="mb-4">
                                <p class="text-xs text-surface-500 mb-1">{{ __('messages.order_number') }}</p>
                                <p class="text-lg font-bold text-surface-900">#{{ $conversation->order->order_number }}</p>
                            </div>

                            <!-- Customer Info -->
                            <div class="mb-4 p-3 bg-surface-50 rounded-lg">
                                <p class="text-xs text-surface-500 mb-2">{{ __('messages.customer') }}</p>
                                <p class="font-semibold text-surface-900">{{ $conversation->customer->name }}</p>
                                <p class="text-xs text-surface-500 mt-1">{{ $conversation->customer->email }}</p>
                            </div>

                            <!-- Last Message Preview -->
                            @if($conversation->lastMessage)
                                <div class="mb-4 p-3 bg-surface-50 rounded-lg border-l-2 border-primary">
                                    <p class="text-xs text-surface-500 mb-1">{{ __('messages.last_message') }}</p>
                                    <p class="text-sm text-surface-700 line-clamp-2">
                                        {{ Str::limit($conversation->lastMessage->body ?? '[' . __('messages.attachment') . ']', 80) }}
                                    </p>
                                    <p class="text-xs text-surface-400 mt-2">
                                        {{ $conversation->lastMessage->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @else
                                <div class="mb-4 p-3 bg-surface-50 rounded-lg">
                                    <p class="text-sm text-surface-400 italic">{{ __('messages.no_messages_yet') }}</p>
                                </div>
                            @endif

                            <!-- Order Status -->
                            <div class="flex items-center justify-between pt-4 border-t border-surface-200">
                                <span class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 rounded-full">
                                    <span class="text-xs font-medium text-primary">
                                        {{ ucfirst($conversation->order->status) }}
                                    </span>
                                </span>
                                <i class="material-symbols-outlined text-primary group-hover:translate-x-1 transition">arrow_outward</i>
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
                <p class="text-surface-500 max-w-md text-center">{{ __('messages.no_conversations_desc') }}</p>
            </div>
        @endif
    </div>
</div>

<style>
    [dir="rtl"] .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    [dir="rtl"] .border-l-2 {
        border-right: 2px solid;
        border-left: none;
    }

    [dir="rtl"] .group-hover\:translate-x-1:hover {
        transform: translateX(-0.25rem);
    }
</style>
@endsection
