{{-- Quantity Stepper Component --}}
@props(['quantity' => 1, 'itemId'])

<div class="flex items-center bg-surface-container-highest rounded-full px-2 py-1">
    <button 
        type="button"
        class="w-8 h-8 flex items-center justify-center text-primary font-black hover:bg-primary/10 rounded-full transition-colors"
        wire:click="decreaseQuantity({{ $itemId }})"
        @if($quantity <= 1) disabled @endif
    >
        -
    </button>
    <span class="px-3 font-bold text-on-surface">{{ $quantity }} {{ $unit ?? 'كجم' }}</span>
    <button 
        type="button"
        class="w-8 h-8 flex items-center justify-center text-primary font-black hover:bg-primary/10 rounded-full transition-colors"
        wire:click="increaseQuantity({{ $itemId }})"
    >
        +
    </button>
</div>
