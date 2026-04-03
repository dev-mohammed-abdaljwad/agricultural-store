{{-- Order Confirmed Main Page Component (Screen 5A) --}}
@props(['order' => [], 'items' => [], 'totalAmount' => 0])

@php
    // Determine timeline step based on order status
    $statusSteps = [
        'pending' => 1,
        'quote_sent' => 2,
        'quote_accepted' => 3,
        'confirmed' => 4,
        'in_transit' => 5,
        'delivered' => 5,
    ];
    $currentStep = $statusSteps[$order['status'] ?? 'confirmed'] ?? 4;
@endphp

<div class="max-w-5xl mx-auto px-6 py-8">
    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-2 mb-8 text-sm text-on-surface-variant font-medium font-body">
        <a class="hover:text-primary" href="{{ route('orders.index') }}">الطلبات</a>
        <span class="material-symbols-outlined text-sm">chevron_left</span>
        <a class="hover:text-primary" href="#">عرض سعر #{{ $order['number'] ?? 'ORD-2024-0000' }}</a>
        <span class="material-symbols-outlined text-sm">chevron_left</span>
        <span class="text-primary font-bold">تأكيد القبول</span>
    </nav>
    
    {{-- Confirmation Banner + Total Card --}}
    <x-order.confirmation-banner 
        :orderNumber="$order['number'] ?? ''"
        :totalAmount="$totalAmount"
        :status="$order['status'] ?? 'confirmed'"
    />
    
    {{-- Order Status Timeline --}}
    <x-order.order-status-timeline :currentStep="$currentStep" />
    
    {{-- Confirmed Items Table --}}
    <x-order.confirmed-items-table 
        :items="$items"
        :referenceNumber="$order['reference_number'] ?? 'RFQ-9921-X'"
        :totalAmount="$totalAmount"
    />
    
    {{-- Important Notes & Action Buttons --}}
    <x-order.order-notes-and-actions :orderNumber="$order['number'] ?? ''" />
</div>
