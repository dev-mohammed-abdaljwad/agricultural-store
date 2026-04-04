{{-- Order Items Summary Component (Left Column) --}}
@props(['items' => [], 'infoBox' => true])

<div class="space-y-6 md:space-y-8">
    {{-- Title --}}
    <div class="space-y-1 md:space-y-2">
        <h1 class="text-2xl md:text-4xl font-black text-primary tracking-tight font-headline">ملخص السلة</h1>
        <p class="text-on-surface-variant text-sm md:text-lg">راجع المنتجات المختارة قبل إتمام الطلب</p>
    </div>
    
    {{-- Products Grid --}}
    <div class="grid grid-cols-1 gap-4 md:gap-6">
        @forelse($items as $item)
            {{-- Item Card --}}
            <div class="bg-surface-container-lowest p-4 md:p-6 flex flex-col sm:flex-row gap-4 md:gap-6 group hover:bg-surface transition-colors duration-300">
                {{-- Product Image --}}
                <div class="w-full h-40 sm:w-40 md:w-48 sm:h-40 md:h-48 flex-shrink-0 bg-surface-container-low overflow-hidden flex items-center justify-center rounded-lg">
                    @if($item['image'] ?? null)
                        <img 
                            alt="{{ $item['name'] ?? 'منتج' }}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            src="{{ $item['image'] }}"
                        />
                    @else
                        <span class="material-symbols-outlined text-outline-variant text-3xl md:text-5xl">image</span>
                    @endif
                </div>
                
                {{-- Product Info --}}
                <div class="flex-grow">
                    {{-- Header with Delete Button --}}
                    <div class="flex justify-between items-start gap-2">
                        <div class="min-w-0">
                            {{-- Badge --}}
                            <span class="inline-block px-2 sm:px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full mb-2">
                                {{ $item['badge'] ?? 'منتج متميز' }}
                            </span>
                            
                            {{-- Name --}}
                            <h3 class="text-lg md:text-xl font-bold text-primary font-headline line-clamp-2">
                                {{ $item['name'] ?? 'اسم المنتج' }}
                            </h3>
                            
                            {{-- Quantity --}}
                            <p class="text-on-surface-variant text-xs sm:text-sm mt-1">
                                الكمية: {{ $item['quantity'] ?? 0 }} {{ $item['quantity_unit'] ?? 'جوال' }}
                            </p>
                        </div>
                        
                        {{-- Delete Button --}}
                        <form method="POST" action="{{ route('cart.remove', $item['cart_item_id']) }}" 
                              style="display: inline;" 
                              onsubmit="return confirm('هل تريد حذف هذا المنتج من السلة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-error/60 hover:text-error transition-colors p-2 shrink-0" title="حذف">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                    
                    {{-- Meta Tags --}}
                    <div class="mt-3 md:mt-4 flex gap-2 md:gap-4 flex-wrap text-xs md:text-sm">
                        @if($item['origin'] ?? false)
                            <span class="bg-surface-container-high px-2 md:px-3 py-1 text-on-surface-variant">
                                المنشأ: {{ $item['origin'] }}
                            </span>
                        @endif
                        
                        @if($item['season'] ?? false)
                            <span class="bg-surface-container-high px-2 md:px-3 py-1 text-on-surface-variant">
                                الموسم: {{ $item['season'] }}
                            </span>
                        @endif
                        
                        @if($item['type'] ?? false)
                            <span class="bg-surface-container-high px-2 md:px-3 py-1 text-on-surface-variant">
                                النوع: {{ $item['type'] }}
                            </span>
                        @endif
                        
                        @if($item['concentration'] ?? false)
                            <span class="bg-surface-container-high px-2 md:px-3 py-1 text-on-surface-variant">
                                التركيز: {{ $item['concentration'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="bg-surface-container-lowest rounded-xl p-8 md:p-12 text-center">
                <span class="material-symbols-outlined text-6xl md:text-8xl text-outline-variant block mb-4">shopping_cart</span>
                <h3 class="text-lg md:text-xl font-bold text-on-surface-variant mb-2">سلتك فارغة</h3>
            </div>
        @endforelse
    </div>
    
    {{-- Info Box --}}
    @if($infoBox)
        <div class="bg-secondary-container/30 p-4 md:p-6 flex gap-3 md:gap-4 rounded-lg">
            <div class="bg-secondary-container p-2 md:p-3 rounded-full flex-shrink-0">
                <span class="material-symbols-outlined text-on-secondary-container text-lg md:text-2xl">history_toggle_off</span>
            </div>
            <div>
                <h4 class="font-bold text-on-secondary-container text-base md:text-lg font-headline">نظام التسعير المرن</h4>
                <p class="text-on-secondary-container/80 leading-relaxed mt-1 text-xs md:text-sm">
                    سيتم إرسال عرض السعر النهائي شاملاً رسوم الشحن إلى حسابك خلال 24 ساعة من إرسال الطلب.
                </p>
            </div>
        </div>
    @endif
</div>
