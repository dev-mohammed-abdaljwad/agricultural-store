@extends('layouts.customer')

@section('title', 'طلباتي')

@section('content')
<div class="max-w-6xl">
    {{-- Page Header --}}
    <header class="mb-12">
        <h1 class="text-4xl font-headline font-black text-on-surface mb-2">طلباتي</h1>
        <p class="text-on-surface-variant">إدارة جميع طلباتك والاطلاع على حالتها</p>
    </header>

    {{-- Orders List --}}
    @if($orders->isNotEmpty())
    <div class="space-y-6">
        @foreach($orders as $order)
        <div class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/20 editorial-shadow">
            {{-- Order Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div>
                    <h3 class="text-lg font-headline font-bold text-on-surface">{{ $order->order_number }}</h3>
                    <p class="text-sm text-on-surface-variant">{{ $order->created_at->format('d F Y') }}</p>
                </div>

                {{-- Status Badge --}}
                <div class="flex items-center gap-2">
                    @if($order->status === 'placed')
                    <span class="bg-warning-container text-on-warning-container px-4 py-2 rounded-full text-sm font-bold">
                        <span class="material-symbols-outlined inline text-lg">schedule</span>
                        قيد المراجعة
                    </span>
                    @elseif($order->status === 'quote_sent')
                    <span class="bg-info-container text-on-info-container px-4 py-2 rounded-full text-sm font-bold">
                        <span class="material-symbols-outlined inline text-lg">rate_review</span>
                        عرض جديد
                    </span>
                    @elseif($order->status === 'quote_accepted')
                    <span class="bg-success-container text-on-success-container px-4 py-2 rounded-full text-sm font-bold">
                        <span class="material-symbols-outlined inline text-lg">check_circle</span>
                        مؤكد
                    </span>
                    @elseif($order->status === 'quote_rejected')
                    <span class="bg-error-container text-on-error-container px-4 py-2 rounded-full text-sm font-bold">
                        <span class="material-symbols-outlined inline text-lg">error</span>
                        عرض مرفوض
                    </span>
                    @else
                    <span class="bg-surface-container text-on-surface px-4 py-2 rounded-full text-sm font-bold">
                        {{ $order->status }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Order Items Summary --}}
            <div class="mb-6 pb-6 border-b border-outline-variant/20">
                <p class="text-sm text-on-surface-variant mb-3">
                    {{ $order->items->count() }} منتج{{ $order->items->count() > 1 ? 'ات' : '' }}
                </p>
                <div class="flex gap-3 flex-wrap">
                    @foreach($order->items->take(3) as $item)
                    <div class="flex items-center gap-2 bg-surface-container px-3 py-2 rounded-lg text-sm">
                        @if($item->product->images->first())
                        <img src="{{ $item->product->images->first()->url }}" alt="{{ $item->product->name }}" class="w-8 h-8 rounded object-cover"/>
                        @endif
                        <span class="text-on-surface">{{ $item->product->name }}</span>
                        <span class="text-on-surface-variant">×{{ $item->quantity }}</span>
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <div class="px-3 py-2 text-sm text-on-surface-variant">
                        و{{ $order->items->count() - 3 }} منتج آخر
                    </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col md:flex-row gap-3">
                @if($order->status === 'placed')
                <a href="{{ route('customer.orders.quote-sent', ['order' => $order]) }}" class="flex-1 bg-primary text-on-primary px-4 py-3 rounded-lg font-bold text-center transition-all hover:bg-primary/90">
                    عرض التفاصيل
                </a>
                @elseif($order->status === 'quote_sent')
                <a href="{{ route('customer.orders.quote-sent', ['order' => $order]) }}" class="flex-1 bg-primary text-on-primary px-4 py-3 rounded-lg font-bold text-center transition-all hover:bg-primary/90">
                    مراجعة العرض
                </a>
                @elseif($order->status === 'quote_accepted')
                <a href="{{ route('customer.orders.confirmed', ['order' => $order]) }}" class="flex-1 bg-primary text-on-primary px-4 py-3 rounded-lg font-bold text-center transition-all hover:bg-primary/90">
                    متابعة الطلب
                </a>
                @elseif($order->status === 'quote_rejected')
                <a href="{{ route('customer.orders.quote-rejected', ['order' => $order]) }}" class="flex-1 bg-primary text-on-primary px-4 py-3 rounded-lg font-bold text-center transition-all hover:bg-primary/90">
                    عرض التفاصيل
                </a>
                @endif

                <button class="flex-1 bg-surface-container text-on-surface px-4 py-3 rounded-lg font-bold transition-all hover:bg-surface-container-high flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">message</span>
                    التواصل
                </button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @endif

    @else
    {{-- Empty State --}}
    <div class="bg-surface-container-lowest rounded-xl p-12 text-center border border-outline-variant/20">
        <span class="material-symbols-outlined text-6xl text-on-surface-variant/40 block mb-4">shopping_bag</span>
        <h3 class="text-xl font-headline font-bold text-on-surface mb-2">لا توجد طلبات</h3>
        <p class="text-on-surface-variant mb-6">ابدأ رحلتك معنا بطلب أول</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-primary text-on-primary px-6 py-3 rounded-lg font-bold transition-all hover:bg-primary/90">
            تصفح المنتجات
        </a>
    </div>
    @endif
</div>
@endsection
