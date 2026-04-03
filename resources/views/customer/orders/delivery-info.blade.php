{{-- resources/views/customer/orders/delivery-info.blade.php --}}
@extends('layouts.customer')

@section('title', 'تفاصيل التسليم')

@section('content')
    <x-order.delivery-info 
        :items="$items"
        :action="route('orders.create')"
    />
@endsection
