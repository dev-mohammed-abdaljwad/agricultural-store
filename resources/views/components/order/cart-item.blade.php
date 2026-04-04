{{-- Cart Item Component --}}
@props(['item'])

<div class="bg-surface-container-lowest p-3 md:p-4 rounded-xl flex flex-col sm:flex-row gap-3 md:gap-4 shadow-[0_4px_24px_rgba(21,66,18,0.04)] group transition-all hover:translate-y-[-2px]">
    {{-- Product Image --}}
    <div class="w-full h-40 sm:w-24 sm:h-24 rounded-lg bg-surface-container overflow-hidden sm:shrink-0 flex items-center justify-center">
        @if($item['image'] ?? null)
            <img 
                alt="{{ $item['name'] ?? 'منتج' }}" 
                class="w-full h-full object-cover"
                src="{{ $item['image'] }}"
            />
        @else
            <span class="material-symbols-outlined text-outline-variant text-2xl sm:text-3xl">image</span>
        @endif
    </div>
    
    {{-- Product Info --}}
    <div class="flex-1 min-w-0 flex flex-col justify-center">
        {{-- Category Badge --}}
        <span class="text-[10px] sm:text-xs font-bold text-on-secondary-fixed-variant bg-secondary-container px-2 py-0.5 rounded-full inline-block w-fit">
            {{ $item['category'] ?? 'منتج' }}
        </span>
        
        {{-- Product Name --}}
        <h3 class="text-base sm:text-lg md:text-xl font-bold text-on-surface mt-1 line-clamp-2">
            {{ $item['name'] ?? 'اسم المنتج' }}
        </h3>
        
        {{-- Supplier/Source --}}
        <p class="text-on-surface-variant text-xs sm:text-sm mt-1">
            {{ $item['supplier'] ?? 'المورد' }}
        </p>
    </div>
    
    {{-- Quantity Stepper & Delete (Mobile Stack) --}}
    <div class="flex items-center justify-between sm:flex-col gap-2 sm:gap-3">
        <x-order.quantity-stepper 
            :quantity="$item['quantity'] ?? 1" 
            :itemId="$item['cart_item_id'] ?? 0"
            :unit="$item['unit'] ?? 'كجم'"
        />
        
        {{-- Delete Button --}}
        <form method="POST" action="{{ route('cart.remove', $item['cart_item_id'] ?? 0) }}" 
              style="display: inline;" 
              onsubmit="return confirm('هل تريد حذف هذا المنتج من السلة؟');">
            @csrf
            @method('DELETE')
            <button type="submit" class="material-symbols-outlined text-error hover:bg-error/10 rounded-full p-2 transition-colors shrink-0" title="حذف">
                delete
            </button>
        </form>
    </div>
</div>
