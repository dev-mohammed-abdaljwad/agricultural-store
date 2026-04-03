{{-- resources/views/customer/orders/quote-sent.blade.php --}}
@extends('layouts.customer')

@section('title', 'عرض السعر')

@section('content')
    <x-order.quote-sent 
        :orderNumber="$order['number'] ?? 'QT-99281'"
        :orderDate="$order['date'] ?? '12 أكتوبر 2023'"
        :items="$quoteItems"
        :subtotal="$quote['subtotal'] ?? 0"
        :shipping="$quote['shipping'] ?? 0"
        :tax="$quote['tax'] ?? 0"
        :total="$quote['total'] ?? 0"
        :acceptUrl="route('customer.orders.acceptQuote', ['order' => $order['id'] ?? 0, 'quote' => $quote['id'] ?? 0])"
        :rejectUrl="route('customer.orders.rejectQuote', ['order' => $order['id'] ?? 0, 'quote' => $quote['id'] ?? 0])"
        :trackingSteps="$trackingSteps"
        :messages="$messages"
    />
@endsection
