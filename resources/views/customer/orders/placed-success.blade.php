{{-- resources/views/customer/orders/placed-success.blade.php --}}
@extends('layouts.customer')

@section('title', 'طلبك بنجاح')

@section('content')
    <x-order.order-placed-success 
        :orderNumber="$order['number'] ?? 'NH-2024-0042'"
        :status="$order['status'] ?? 'قيد المراجعة'"
        :trackOrderUrl="route('orders.show', ['order' => $order['id'] ?? 0])"
        :continueShoppingUrl="route('products.index')"
    />
@endsection
