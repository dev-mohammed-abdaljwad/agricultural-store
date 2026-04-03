{{-- Action Buttons Component --}}
@props(['trackOrderUrl' => '', 'continueShoppingUrl' => ''])

<div class="flex flex-col gap-3 sm:gap-4 w-full">
    {{-- Track Order Button (Primary) --}}
    <a 
        href="{{ $trackOrderUrl }}"
        class="w-full bg-primary text-on-primary font-headline font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-xl hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-2 text-sm sm:text-base"
    >
        <span>متابعة طلبي</span>
        <span class="material-symbols-outlined text-base sm:text-lg">analytics</span>
    </a>
    
    {{-- Continue Shopping Button (Secondary) --}}
    <a 
        href="{{ $continueShoppingUrl }}"
        class="w-full bg-secondary-container text-on-secondary-container font-headline font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-xl hover:bg-opacity-80 active:scale-95 transition-all flex items-center justify-center gap-2 text-sm sm:text-base"
    >
        <span>مواصلة التسوق</span>
        <span class="material-symbols-outlined text-base sm:text-lg">shopping_basket</span>
    </a>
</div>
