{{-- Order Placed Success: Main Card Component (Left Column) --}}
@props(['orderNumber' => '', 'status' => 'قيد المراجعة', 'trackOrderUrl' => '', 'continueShoppingUrl' => ''])

<div class="w-full bg-surface-container-lowest rounded-2xl p-6 sm:p-8 md:p-10 lg:p-12 shadow-sm border-0 flex flex-col items-center text-center">
    {{-- Success Icon --}}
    <div class="mb-6 sm:mb-8">
        <x-order.success-icon />
    </div>
    
    {{-- Headline --}}
    <h1 class="font-headline font-black text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-primary mb-4 sm:mb-6 leading-tight">
        تم إرسال طلبك بنجاح!
    </h1>
    
    {{-- Description --}}
    <p class="text-on-surface-variant text-sm sm:text-base md:text-lg mb-8 sm:mb-10 max-w-lg font-body leading-relaxed">
        شكراً لثقتك في حصاد. طلبك الآن في مرحلة المعالجة وسيتم التواصل معك قريباً.
    </p>
    
    {{-- Order Metadata --}}
    <div class="w-full mb-8 sm:mb-10">
        <x-order.order-metadata 
            :orderNumber="$orderNumber"
            :status="$status"
        />
    </div>
    
    {{-- Action Buttons --}}
    <div class="w-full">
        <x-order.action-buttons 
            :trackOrderUrl="$trackOrderUrl"
            :continueShoppingUrl="$continueShoppingUrl"
        />
    </div>
</div>
