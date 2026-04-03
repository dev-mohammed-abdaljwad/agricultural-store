{{-- Quote Sent: Sidebar Component (Left Column) --}}
@props(['acceptUrl' => '', 'rejectUrl' => '', 'trackingSteps' => []])

<aside class="lg:col-span-4 order-2 lg:order-1 space-y-6">
    {{-- Alert Banner --}}
    <x-order.alert-banner message="وصلك عرض سعر جديد" />
    
    {{-- Decision Card --}}
    <x-order.quote-decision-card 
        :acceptUrl="$acceptUrl"
        :rejectUrl="$rejectUrl"
    />
    
    {{-- Tracking Timeline --}}
    <x-order.order-tracking-timeline :steps="$trackingSteps" />
</aside>
