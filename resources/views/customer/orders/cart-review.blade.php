{{-- resources/views/customer/orders/cart-review.blade.php --}}
@extends('layouts.customer')

@section('title', 'مراجعة الطلب')

@section('content')
    <x-order.cart-review 
        :items="$cartItems"
        :itemCount="count($cartItems)"
        :totalWeight="$totalWeight ?? 0"
    />
@endsection
