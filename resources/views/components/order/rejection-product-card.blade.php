@props([
    'name' => '',
    'quantity' => '',
    'image' => null,
    'badge' => 'جودة ممتازة',
    'oldPrice' => null,
    'currency' => 'ج.م',
])

<div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden group">
    {{-- Badge --}}
    @if($badge)
    <div class="absolute top-4 left-4 bg-tertiary-fixed text-on-tertiary-fixed px-3 py-1 rounded-full text-xs font-bold z-10 shadow-sm">
        {{ $badge }}
    </div>
    @endif

    {{-- Image --}}
    <div class="aspect-video w-full mb-4 overflow-hidden rounded-lg">
        @if($image)
        <img 
            alt="{{ $name }}"
            src="{{ $image }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
        />
        @else
        <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant">image</span>
        </div>
        @endif
    </div>

    {{-- Name --}}
    <h3 class="font-headline font-bold text-lg mb-1">
        {{ $name }}
    </h3>

    {{-- Quantity --}}
    <p class="text-sm text-on-surface-variant mb-4">
        الكمية المطلوبة: {{ $quantity }}
    </p>

    {{-- Price Section --}}
    @if($oldPrice)
    <div class="flex justify-between items-end border-t border-outline-variant/10 pt-4">
        <span class="text-xs text-on-surface-variant">السعر السابق المرفوض</span>
        <span class="text-error font-bold line-through">
            {{ number_format($oldPrice) }} {{ $currency }} / طن
        </span>
    </div>
    @endif

    {{ $slot }}
</div>
