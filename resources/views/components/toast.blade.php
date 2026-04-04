@props(['type' => 'info', 'message' => '', 'duration' => 4000])

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    x-init="setTimeout(() => show = false, {{ $duration }})"
    @click="show = false"
    class="fixed bottom-6 right-6 z-50 max-w-md cursor-pointer"
>
    <div class="rounded-lg shadow-lg border p-4 flex items-start gap-3
        @if($type === 'success') bg-primary-fixed text-primary border-primary
        @elseif($type === 'error') bg-error-container text-error border-error
        @elseif($type === 'warning') bg-yellow-100 text-yellow-900 border-yellow-300
        @else bg-surface-container-low text-on-surface border-outline-variant
        @endif
    ">
        <span class="material-symbols-outlined flex-shrink-0 text-lg">
            @if($type === 'success') check_circle
            @elseif($type === 'error') error
            @elseif($type === 'warning') warning
            @else info
            @endif
        </span>
        <div class="flex-1">
            {{ $slot ?? $message }}
        </div>
        <button 
            @click="show = false"
            class="flex-shrink-0 hover:opacity-70 transition-opacity"
        >
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>
</div>
