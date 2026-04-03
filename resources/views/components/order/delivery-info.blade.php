{{-- Delivery Info Main Component (Screen 2) --}}
@props(['items' => [], 'action' => ''])

<main class="pt-24 md:pt-32 pb-20 md:pb-24 px-4 sm:px-6 md:px-8 lg:px-12 max-w-7xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-8 md:gap-12 items-start">
        {{-- Left Column: Order Summary --}}
        <div class="w-full lg:w-3/5">
            <x-order.order-items-summary :items="$items" />
        </div>
        
        {{-- Right Column: Shipping Form --}}
        <div class="w-full lg:w-2/5 lg:sticky lg:top-24">
            <x-order.shipping-form :action="$action" />
        </div>
    </div>
</main>
