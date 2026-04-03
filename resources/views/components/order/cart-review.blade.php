{{-- Cart Review Screen (Screen 1) Component --}}
@props(['items' => [], 'itemCount' => 0, 'totalWeight' => 0])

<main class="pt-20 md:pt-28 px-4 sm:px-6 max-w-6xl mx-auto pb-24">
    {{-- Page Title --}}
    <div class="flex items-center gap-3 mb-6 md:mb-8">
        <div class="w-10 h-10 md:w-12 md:h-12 bg-primary-fixed flex items-center justify-center rounded-xl shadow-sm shrink-0">
            <span class="material-symbols-outlined text-primary text-2xl md:text-3xl">shopping_cart</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-primary tracking-tight font-headline">مراجعة الطلب</h1>
    </div>
    
    {{-- Notice Banner --}}
    <x-order.notice-banner>
        الأسعار ستُحدد من قِبل فريقنا بعد مراجعة طلبك — ستصلك إشعار بعرض السعر خلال 24 ساعة
    </x-order.notice-banner>
    
    {{-- Main Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
        {{-- Cart Items List --}}
        <div class="md:col-span-2 space-y-3 md:space-y-4">
            @forelse($items as $item)
                <x-order.cart-item :item="$item" />
            @empty
                {{-- Empty State --}}
                <div class="bg-surface-container-lowest rounded-xl p-8 md:p-12 text-center">
                    <span class="material-symbols-outlined text-6xl md:text-8xl text-outline-variant block mb-4">shopping_cart</span>
                    <h3 class="text-lg md:text-xl font-bold text-on-surface-variant mb-2 md:mb-4">سلتك فارغة</h3>
                    <p class="text-on-surface-variant text-xs md:text-sm mb-4 md:mb-6">لم تضف أي منتجات حتى الآن</p>
                    <a 
                        href="{{ route('products.index') }}"
                        class="inline-block bg-primary text-on-primary px-6 md:px-8 py-2 md:py-3 rounded-xl font-bold transition-all hover:opacity-90 active:scale-95 text-sm md:text-base"
                    >
                        تصفح المنتجات
                    </a>
                </div>
            @endforelse
        </div>
        
        {{-- Summary Sidebar --}}
        <div class="md:col-span-1">
            <x-order.cart-summary 
                :itemCount="$itemCount ?? count($items)"
                :totalWeight="$totalWeight ?? 0"
            />
        </div>
    </div>
</main>
