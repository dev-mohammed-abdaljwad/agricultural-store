{{-- resources/views/quotes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'عروض الأسعار')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-surface to-surface-dim p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-on-surface mb-2">عروض الأسعار الخاصة بي</h1>
            <p class="text-on-surface-variant">عرض جميع عروض الأسعار والطلبات الخاصة بك</p>
        </div>

        <!-- Quotes Grid -->
        @if($quotes->count() > 0)
            <div class="grid md:grid-cols-2 gap-6">
                @foreach($quotes as $order)
                    @foreach($order->quotes as $quote)
                        <div class="bg-surface-bright rounded-lg shadow-sm border border-outline p-6 hover:shadow-md transition-shadow">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-4 pb-4 border-b border-outline">
                                <div>
                                    <h3 class="text-lg font-bold text-on-surface">{{ $order->order_number }}</h3>
                                    <p class="text-sm text-on-surface-variant">{{ $order->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="flex flex-col items-end">
                                    @switch($quote->status)
                                        @case('draft')
                                            <span class="px-3 py-1 bg-secondary text-on-secondary rounded-full text-xs font-bold">مسودة</span>
                                            @break
                                        @case('pending')
                                            <span class="px-3 py-1 bg-primary text-on-primary rounded-full text-xs font-bold">قيد الانتظار</span>
                                            @break
                                        @case('accepted')
                                            <span class="px-3 py-1 bg-success text-on-success rounded-full text-xs font-bold">مقبول</span>
                                            @break
                                        @case('rejected')
                                            <span class="px-3 py-1 bg-error text-on-error rounded-full text-xs font-bold">مرفوض</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>

                            <!-- Items Summary -->
                            <div class="mb-4">
                                <p class="text-sm text-on-surface-variant mb-2 font-medium">المنتجات:</p>
                                <div class="space-y-2">
                                    @foreach($quote->items as $item)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-on-surface">{{ $item->orderItem->product->name }}</span>
                                            <span class="text-on-surface-variant">{{ $item->orderItem->quantity }} × {{ $item->unit_price }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Total & Expiry -->
                            <div class="bg-on-surface/5 rounded-lg p-4 mb-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-on-surface-variant">الإجمالي:</span>
                                    <span class="text-lg font-bold text-on-surface">{{ number_format($quote->total_amount, 2) }} ج.م</span>
                                </div>
                                @if($quote->expires_at)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-on-surface-variant">ينتهي:</span>
                                        <span class="text-on-surface">{{ $quote->expires_at->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3">
                                <a href="{{ route('orders.show', $order) }}" class="flex-1 bg-primary text-on-primary py-2 rounded-lg text-center font-bold hover:bg-primary/90 transition-colors text-sm">
                                    عرض التفاصيل
                                </a>
                                @if($quote->status === 'pending')
                                    <form method="POST" action="{{ route('customer.orders.acceptQuote', [$order->id, $quote->id]) }}" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-success text-on-success py-2 rounded-lg font-bold hover:bg-success/90 transition-colors text-sm">
                                            قبول
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('customer.orders.rejectQuote', [$order->id, $quote->id]) }}" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-error text-on-error py-2 rounded-lg font-bold hover:bg-error/90 transition-colors text-sm">
                                            رفض
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Pagination -->
            @if($quotes->hasPages())
                <div class="mt-8">
                    {{ $quotes->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-surface-bright rounded-lg border border-outline p-12 text-center">
                <div class="mb-4">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant opacity-50">description</span>
                </div>
                <h3 class="text-xl font-bold text-on-surface mb-2">لا توجد عروض أسعار</h3>
                <p class="text-on-surface-variant mb-6">لم تقم بإنشاء أي طلبات عروض أسعار حتى الآن</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-primary text-on-primary px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition-colors">
                    تصفح المنتجات
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
