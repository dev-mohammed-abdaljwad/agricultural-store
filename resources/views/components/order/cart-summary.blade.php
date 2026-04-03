{{-- Cart Summary Card Component --}}
@props(['itemCount' => 0, 'totalWeight' => 0, 'showDeliveryFee' => false, 'deliveryFee' => 0])

<div class="bg-surface-container-high p-6 md:p-8 rounded-2xl md:sticky md:top-24">
    <h2 class="text-xl md:text-2xl font-black text-primary mb-4 md:mb-6 font-headline">ملخص الطلب</h2>
    
    {{-- Summary Items --}}
    <div class="space-y-3 md:space-y-4">
        {{-- Item Count --}}
        <div class="flex justify-between items-center text-on-surface-variant text-sm md:text-base">
            <span class="font-medium">عدد المنتجات</span>
            <span class="font-bold text-on-surface">{{ $itemCount }} {{ $itemCount == 1 ? 'منتج' : 'منتجات' }}</span>
        </div>
        
        {{-- Total Weight --}}
        <div class="flex justify-between items-center text-on-surface-variant text-sm md:text-base">
            <span class="font-medium">وزن الحمولة الكلي</span>
            <span class="font-bold text-on-surface">{{ number_format($totalWeight) }} كجم</span>
        </div>
        
        {{-- Delivery Fee --}}
        <div class="flex justify-between items-center text-on-surface-variant text-sm md:text-base">
            <span class="font-medium">رسوم التوصيل</span>
            <span class="text-secondary font-bold">
                @if($showDeliveryFee && $deliveryFee > 0)
                    {{ number_format($deliveryFee) }} ج.م
                @else
                    ستُحدد لاحقاً
                @endif
            </span>
        </div>
        
        {{-- Divider --}}
        <div class="pt-4 md:pt-6 mt-4 md:mt-6 border-t border-outline-variant/30">
            <div class="flex justify-between items-end gap-2">
                <div>
                    <span class="text-primary font-black text-lg md:text-xl">الإجمالي</span>
                    <p class="text-[10px] md:text-xs text-on-surface-variant mt-1">تقديري حتى مراجعة الفريق</p>
                </div>
                <span class="text-primary font-black text-lg md:text-2xl text-left">
                    سيتم إخطارك بعرض السعر
                </span>
            </div>
        </div>
    </div>
    
    {{-- Continue Button --}}
    <a 
        href="{{ route('orders.delivery-info') }}"
        class="w-full mt-6 md:mt-8 bg-primary text-on-primary py-3 md:py-4 rounded-xl font-bold text-sm md:text-lg flex items-center justify-center gap-2 transition-all hover:opacity-90 active:scale-95 shadow-lg shadow-primary/10"
    >
        <span>متابعة لتحديد العنوان</span>
        <span class="material-symbols-outlined text-sm md:text-base">arrow_back</span>
    </a>
    
    {{-- Helper Text --}}
    <p class="text-center text-on-surface-variant text-xs md:text-sm mt-4 leading-relaxed">
        بمجرد إرسال الطلب، سيقوم أحد مندوبينا بالتواصل معك خلال 24 ساعة لتحديد الأسعار النهائية وتفاصيل الشحن.
    </p>
</div>
