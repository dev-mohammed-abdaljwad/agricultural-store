{{-- Order Placed Success: Main Page Component (Screen 3) --}}
@props(['orderNumber' => '', 'status' => 'قيد المراجعة', 'trackOrderUrl' => '', 'continueShoppingUrl' => ''])

<main class="min-h-screen flex items-center justify-center p-3 sm:p-4 md:p-8 pt-24 md:pt-8">
    {{-- Success Canvas --}}
    <div class="max-w-5xl w-full flex flex-col lg:flex-row-reverse gap-6 sm:gap-8 items-start lg:items-stretch">
        {{-- Left: Main Card (60%) --}}
        <div class="w-full lg:w-3/5">
            <x-order.success-card 
                :orderNumber="$orderNumber"
                :status="$status"
                :trackOrderUrl="$trackOrderUrl"
                :continueShoppingUrl="$continueShoppingUrl"
            />
        </div>
        
        {{-- Right: What Happens Next (40%) --}}
        <div class="w-full lg:w-2/5 flex flex-col gap-6">
            <x-order.next-steps-section />
            <x-order.support-badge />
        </div>
    </div>
</main>

{{-- Decorative Background Element --}}
<div class="fixed bottom-0 left-0 w-32 sm:w-48 md:w-64 h-32 sm:h-48 md:h-64 opacity-10 pointer-events-none z-0">
    <img 
        alt="نبات أخضر"
        class="w-full h-full object-contain grayscale"
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBkyw_7B_qgn63CcKq5sOgG_60JwTfCzAACixQXf5Imd2ao5LnsZsOLQKX6htDAHQw7sESqIvUuxTK-y68rVM9E3fMayQQy-DTmV4_Go0rWNkSS5H5di1Y69MeNwT4LA_S_rCLc3HL-4wvC3SC7brmYxT0Tj5JLTUazvK9hWz4s6sw7I_j8wTybX93w_xNuevWuO6_rGCm1AgUWb74mRzSJQ8rrja-W8iPLYtN9Irx9ishcldMKbA5nd6X2Y4B16A6RezbMfwYzpMM3"
    />
</div>
