@props(['product'])

<!-- Product Card Component -->
<div class="bg-surface-container-lowest rounded-2xl editorial-shadow overflow-hidden group transition-transform hover:-translate-y-1">
    <div class="relative h-[220px] overflow-hidden">
        @if($product->images->first())
            <img 
                alt="{{ $product->name }}" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                src="{{ $product->images->first()->asset_url }}"
            />
        @else
            <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant">image</span>
            </div>
        @endif
        
        @if($product->is_certified)
            <div class="absolute top-4 left-4 bg-primary-fixed text-on-primary-fixed-variant text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                منتج معتمد
            </div>
        @endif
    </div>
    
    <div class="p-6">
        <span class="text-on-surface-variant text-xs font-bold uppercase tracking-widest mb-2 block">
            {{ $product->category->name }}
        </span>
        
        <h3 class="text-primary text-xl font-bold font-headline mb-4 leading-tight min-h-[3.5rem] line-clamp-2">
            {{ $product->name }}
        </h3>
        
        <div class="flex items-center gap-2 mb-6 text-on-surface-variant text-sm font-medium">
            <span class="material-symbols-outlined text-base">
                @switch($product->unit)
                    @case('kg') scale @break
                    @case('liter') inventory_2 @break
                    @case('box') package @break
                    @case('bag') layers @break
                    @default eco @endswitch
            </span>
            <span>الحد الأدنى: {{ $product->min_order_qty }} {{ $product->unit }}</span>
        </div>
        
        @if(Auth::check())
            <form action="{{ route('quotes.create') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="w-full py-4 bg-primary-fixed text-primary font-bold rounded-lg hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2 active:scale-[0.98]">
                    <span class="material-symbols-outlined">request_quote</span>
                    اطلب عرض سعر
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="w-full py-4 bg-primary-fixed text-primary font-bold rounded-lg hover:bg-primary-fixed-dim transition-colors flex items-center justify-center gap-2 active:scale-[0.98] block text-center">
                <span class="material-symbols-outlined">request_quote</span>
                اطلب عرض سعر
            </a>
        @endif
    </div>
</div>
